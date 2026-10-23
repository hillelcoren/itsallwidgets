<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Support\Export\BusyMarkers;
use App\Support\Export\ExportTables;
use App\Support\Export\RowPager;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * The signed export the new app copies this app's data through. Every route
 * sits behind VerifyIawExport and only answers on the legacy host.
 */
class ExportController extends Controller
{
    /**
     * How many ids one page of the ids route holds.
     */
    const IDS_PER_PAGE = 20000;

    /**
     * The media folders the new app mirrors, keyed by the prefix of each path.
     *
     * @var array
     */
    protected $mediaFolders = [
        'screenshots' => 'public',
        'gifs' => 'public',
        'avatars' => 'public',
        'thumbnails' => 'public',
        'streams' => 'public',
        'mp3s' => 'storage',
    ];

    /**
     * Counts, high-water marks and state, for the new app to plan a copy and
     * check one.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function manifest()
    {
        $this->freshStatistics();

        $autoIncrements = [];

        foreach (DB::select('SELECT TABLE_NAME AS table_name, AUTO_INCREMENT AS auto_increment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()') as $row) {
            $autoIncrements[$row->table_name] = $row->auto_increment === null ? null : (int) $row->auto_increment;
        }

        $tables = [];

        foreach (array_keys(ExportTables::COPIED) as $table) {
            if (! array_key_exists($table, $autoIncrements)) {
                continue;
            }

            $stats = ExportTables::scope($table, DB::table($table))
                ->selectRaw('COUNT(*) AS row_count, MAX(id) AS max_id, MAX(updated_at) AS max_updated_at')
                ->first();

            $tables[$table] = [
                'count' => (int) $stats->row_count,
                'max_id' => $stats->max_id === null ? null : (int) $stats->max_id,
                'max_updated_at' => RowPager::date($stats->max_updated_at),
                'auto_increment' => $autoIncrements[$table],
            ];
        }

        $ours = [];

        foreach (ExportTables::OURS as $table => $key) {
            if (! array_key_exists($table, $autoIncrements)) {
                continue;
            }

            $stats = ExportTables::scope($table, DB::table($table))
                ->selectRaw('COUNT(*) AS row_count, MAX(' . $key . ') AS max_key')
                ->first();

            $ours[$table] = [
                'count' => (int) $stats->row_count,
                'max_key' => $stats->max_key === null ? null : (int) $stats->max_key,
            ];
        }

        $timeZone = DB::selectOne('SELECT @@session.time_zone AS time_zone');
        $utcOffset = DB::selectOne('SELECT TIMEDIFF(NOW(), UTC_TIMESTAMP()) AS o');
        $down = app()->isDownForMaintenance();

        return $this->json([
            'server_time' => time(),
            'time_zone' => $timeZone->time_zone,
            'utc_offset' => (string) $utcOffset->o,
            'down' => $down,
            'down_time' => $this->downTime(),
            'down_by_iaw' => $down ? $this->downByIaw($this->downPayload()) : null,
            'busy' => BusyMarkers::live(),
            'tables' => (object) $tables,
            'ours' => (object) $ours,
        ]);
    }

    /**
     * The ids of a copied table, so the new app can find rows deleted here.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $table
     * @return \Illuminate\Http\JsonResponse
     */
    public function ids(Request $request, $table)
    {
        if (! array_key_exists($table, ExportTables::COPIED)) {
            abort(404);
        }

        $this->freshStatistics();

        $ids = ExportTables::scope($table, DB::table($table))
            ->where('id', '>', $this->afterId($request))
            ->orderBy('id')
            ->limit(self::IDS_PER_PAGE)
            ->pluck('id')
            ->map(function ($id) {
                return (int) $id;
            })
            ->all();

        return $this->json([
            'ids' => $ids,
            'next_after_id' => count($ids) < self::IDS_PER_PAGE ? null : end($ids),
        ]);
    }

    /**
     * A page of a copied table. With ?ours=1 it's only the id and the columns
     * the new app added to the table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $table
     * @return \Illuminate\Http\JsonResponse
     */
    public function rows(Request $request, $table)
    {
        if (! array_key_exists($table, ExportTables::COPIED)) {
            abort(404);
        }

        $oursOnly = $request->query->has('ours');

        if ($oursOnly && $request->query->get('ours') !== '1') {
            abort(404);
        }

        $this->freshStatistics();

        if ($oursOnly) {
            $this->readInUtc();
        }

        $existing = Schema::getColumnListing($table);

        if ($oursOnly) {
            $ourColumns = array_key_exists($table, ExportTables::OUR_COLUMNS) ? ExportTables::OUR_COLUMNS[$table] : [];
            $columns = array_merge(['id'], array_values(array_intersect($ourColumns, $existing)));
        } else {
            $columns = array_values(array_intersect(ExportTables::COPIED[$table], $existing));
        }

        $query = ExportTables::scope($table, DB::table($table))->select($columns);

        return $this->json(RowPager::page($query, 'id', $this->afterId($request), ExportTables::chunkSize($table)));
    }

    /**
     * A page of one of the new app's own tables that still live in this
     * database. The old app doesn't know their schema, so it's every column.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $table
     * @return \Illuminate\Http\JsonResponse
     */
    public function ours(Request $request, $table)
    {
        if (! array_key_exists($table, ExportTables::OURS)) {
            abort(404);
        }

        $this->freshStatistics();
        $this->readInUtc();

        if (! Schema::hasTable($table)) {
            abort(404);
        }

        $query = ExportTables::scope($table, DB::table($table));

        return $this->json(RowPager::page($query, ExportTables::OURS[$table], $this->afterId($request), ExportTables::chunkSize($table)));
    }

    /**
     * The media files changed since a unix time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function media(Request $request)
    {
        $this->freshStatistics();

        $since = (int) $request->query('since', 0);
        $files = [];

        foreach ($this->mediaFolders as $folder => $root) {
            $directory = $root === 'public' ? public_path($folder) : storage_path($folder);

            if (! is_dir($directory)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if (! $file->isFile() || substr($file->getFilename(), 0, 1) === '.') {
                    continue;
                }

                $mtime = $file->getMTime();

                if ($mtime < $since) {
                    continue;
                }

                $relative = str_replace(DIRECTORY_SEPARATOR, '/', substr($file->getPathname(), strlen($directory) + 1));

                $files[] = [
                    'path' => $folder . '/' . $relative,
                    'mtime' => $mtime,
                    'size' => $file->getSize(),
                ];
            }
        }

        usort($files, function ($a, $b) {
            return strcmp($a['path'], $b['path']);
        });

        return $this->json(['files' => $files]);
    }

    /**
     * A podcast file, straight from disk. Unlike /podcast/download it never
     * counts a download.
     *
     * @param  string  $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function mp3($file)
    {
        if (! preg_match('/^episode-\d+\.(mp3|m4a)$/', $file)) {
            abort(404);
        }

        $this->freshStatistics();

        $path = storage_path('mp3s/' . $file);

        if (! is_file($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    /**
     * Put the site into maintenance or bring it back up. A lease (minutes)
     * makes it come back up by itself if the new app never says so.
     *
     * Only maintenance the export put on (tagged "iaw") is touched: a site
     * taken down by hand (artisan down) answers 409 either way.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function maintenance(Request $request)
    {
        $body = json_decode($request->getContent(), true);

        if (! is_array($body) || ! array_key_exists('down', $body) || ! is_bool($body['down'])) {
            return $this->json(['error' => 'The body needs a boolean "down".'], 422);
        }

        $lease = array_key_exists('lease', $body) ? $body['lease'] : null;

        if ($lease !== null && (! is_int($lease) || $lease < 1 || $lease > 120)) {
            return $this->json(['error' => 'The lease must be null or 1 to 120 minutes.'], 422);
        }

        $this->freshStatistics();

        $downFile = storage_path('framework/down');
        $payload = $this->downPayload();

        if (is_file($downFile) && ! $this->downByIaw($payload)) {
            return $this->json(['message' => 'down by hand'], 409);
        }

        if ($body['down']) {
            // Already down: keep the original time, so the new app can tell
            // whether the site came back up in between.
            if (! is_array($payload) || ! isset($payload['time'])) {
                $payload = [
                    'time' => time(),
                    'message' => "We're moving to a new home. Back in a few minutes.",
                    'retry' => 120,
                    'allowed' => [],
                ];
            }

            $payload['iaw'] = true;
            $payload['iaw_until'] = $lease === null ? null : time() + $lease * 60;

            $temporary = $downFile . '.' . getmypid() . '.tmp';
            file_put_contents($temporary, json_encode($payload, JSON_PRETTY_PRINT));
            rename($temporary, $downFile);
        } elseif (is_file($downFile)) {
            unlink($downFile);
        }

        clearstatcache();

        return $this->json([
            'down' => app()->isDownForMaintenance(),
            'down_time' => $this->downTime(),
        ]);
    }

    /**
     * Make information_schema report live AUTO_INCREMENT values (MySQL 8
     * caches them for a day by default). MySQL 5.7 has no such setting.
     *
     * @return void
     */
    protected function freshStatistics()
    {
        try {
            DB::statement('SET SESSION information_schema_stats_expiry = 0');
        } catch (Exception $exception) {
            Log::info('iaw-export: information_schema_stats_expiry not set: ' . $exception->getMessage());
        }
    }

    /**
     * Read timestamps in UTC for the rest of this request, so the new app's
     * own rows come back the way it wrote them.
     *
     * @return void
     */
    protected function readInUtc()
    {
        DB::statement("SET time_zone = '+00:00'");
    }

    /**
     * The after_id of a paged request (the middleware lets digits only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    protected function afterId(Request $request)
    {
        return (int) $request->query('after_id', 0);
    }

    /**
     * When the site went down, from the down file.
     *
     * @return int|null
     */
    protected function downTime()
    {
        $payload = $this->downPayload();

        return is_array($payload) && isset($payload['time']) ? (int) $payload['time'] : null;
    }

    /**
     * The decoded down file, or null when there is none (or it isn't JSON).
     *
     * @return array|null
     */
    protected function downPayload()
    {
        $downFile = storage_path('framework/down');

        if (! is_file($downFile)) {
            return null;
        }

        $payload = json_decode((string) @file_get_contents($downFile), true);

        return is_array($payload) ? $payload : null;
    }

    /**
     * Whether a down file was written by this export (not by artisan down).
     *
     * @param  array|null  $payload
     * @return bool
     */
    protected function downByIaw($payload)
    {
        return is_array($payload) && isset($payload['iaw']) && $payload['iaw'] === true;
    }

    /**
     * A JSON response that keeps unicode and slashes as they are.
     *
     * @param  array  $data
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function json(array $data, $status = 200)
    {
        return response()->json($data, $status, ['Cache-Control' => 'no-store'], RowPager::JSON_FLAGS);
    }
}

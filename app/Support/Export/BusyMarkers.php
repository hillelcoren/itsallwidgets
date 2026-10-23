<?php

namespace App\Support\Export;

use Illuminate\Support\Facades\Log;

/**
 * The markers the itsallwidgets:* commands leave in storage/framework/iaw-busy
 * while they run, so the export manifest can tell the new app to wait.
 *
 * Each marker is "{command}.{pid}" and holds the process start time, so a
 * marker only counts while that very process is still running.
 */
class BusyMarkers
{
    /**
     * How long a marker written inside PHP-FPM (/update, sign-up) may count.
     * The worker lives on after the request, so its pid says little.
     */
    const WEB_SECONDS = 1800;

    /**
     * How long a marker written by a command line run may count.
     */
    const CLI_SECONDS = 43200;

    /**
     * The markers this process has already asked to remove at shutdown.
     *
     * @var array
     */
    protected static $registered = [];

    /**
     * The folder the markers live in.
     *
     * @return string
     */
    public static function directory()
    {
        return storage_path('framework/iaw-busy');
    }

    /**
     * The marker file of a command run by a process.
     *
     * @param  string  $command
     * @param  int  $pid
     * @return string
     */
    public static function path($command, $pid)
    {
        return static::directory() . '/' . preg_replace('/[^A-Za-z0-9_-]+/', '-', $command) . '.' . (int) $pid;
    }

    /**
     * Write this process's marker for a command. Never throws: a marker that
     * can't be written is logged and the command runs anyway.
     *
     * @param  string  $command
     * @return void
     */
    public static function write($command)
    {
        $pid = getmypid();
        $path = static::path($command, $pid);
        $directory = static::directory();

        if (! is_dir($directory) && ! @mkdir($directory, 0755, true) && ! is_dir($directory)) {
            Log::warning('iaw-busy: could not create ' . $directory);

            return;
        }

        $contents = json_encode([
            'command' => $command,
            'pid' => $pid,
            'start' => static::procAvailable() ? static::startTime($pid) : null,
            'at' => time(),
            'sapi' => php_sapi_name(),
        ]);

        // Written aside and renamed, so the manifest never reads half a marker.
        $temporary = $directory . '/.' . basename($path) . '.tmp';

        if (@file_put_contents($temporary, $contents) === false || ! @rename($temporary, $path)) {
            @unlink($temporary);
            Log::warning('iaw-busy: could not write ' . $path);

            return;
        }

        if (! isset(static::$registered[$path])) {
            static::$registered[$path] = true;

            register_shutdown_function(function () use ($path) {
                @unlink($path);
            });
        }
    }

    /**
     * Remove this process's marker for a command.
     *
     * @param  string  $command
     * @return void
     */
    public static function clear($command)
    {
        @unlink(static::path($command, getmypid()));
    }

    /**
     * The commands running right now. Markers of processes that are gone, or
     * older than their backstop, are deleted on the way.
     *
     * @return array
     */
    public static function live()
    {
        $directory = static::directory();
        $busy = [];

        if (! is_dir($directory)) {
            return $busy;
        }

        foreach (scandir($directory) as $name) {
            $path = $directory . '/' . $name;

            if (! preg_match('/^[A-Za-z0-9_-]+\.\d+$/', $name) || ! is_file($path)) {
                continue;
            }

            $marker = json_decode((string) @file_get_contents($path), true);

            if (! is_array($marker) || ! static::counts($marker)) {
                @unlink($path);

                continue;
            }

            $busy[] = (string) $marker['command'];
        }

        $busy = array_values(array_unique($busy));
        sort($busy);

        return $busy;
    }

    /**
     * Whether a marker is recent enough and its process is still running.
     *
     * @param  array  $marker
     * @return bool
     */
    public static function counts(array $marker)
    {
        if (! isset($marker['command'], $marker['pid'], $marker['at'])
            || ! is_string($marker['command'])
            || ! is_int($marker['pid'])
            || ! is_int($marker['at'])) {
            return false;
        }

        $limit = isset($marker['sapi']) && $marker['sapi'] === 'cli' ? self::CLI_SECONDS : self::WEB_SECONDS;

        if (time() - $marker['at'] > $limit) {
            return false;
        }

        return static::isAlive($marker['pid'], array_key_exists('start', $marker) ? $marker['start'] : null);
    }

    /**
     * Whether the process that wrote a marker is still running. With /proc
     * the start time must match too, so a reused pid doesn't count. Never
     * posix_kill: it fails for another user's process.
     *
     * @param  int  $pid
     * @param  string|null  $start
     * @return bool
     */
    public static function isAlive($pid, $start)
    {
        if ($pid < 1) {
            return false;
        }

        if (static::procAvailable()) {
            $current = static::startTime($pid);

            return $current !== null && $start !== null && (string) $start === $current;
        }

        return function_exists('posix_getpgid') ? posix_getpgid($pid) !== false : true;
    }

    /**
     * Whether this system has /proc (Linux does, macOS doesn't).
     *
     * @return bool
     */
    protected static function procAvailable()
    {
        return @file_exists('/proc/self');
    }

    /**
     * A process's start time: field 22 of /proc/{pid}/stat.
     *
     * @param  int  $pid
     * @return string|null
     */
    protected static function startTime($pid)
    {
        $stat = @file_get_contents('/proc/' . (int) $pid . '/stat');

        if (! is_string($stat)) {
            return null;
        }

        // Field 2 is the name in brackets and may hold spaces, so count the
        // fields from the last ")": field 3 comes first.
        $close = strrpos($stat, ')');

        if ($close === false) {
            return null;
        }

        $fields = preg_split('/\s+/', trim(substr($stat, $close + 1)));

        return isset($fields[19]) ? $fields[19] : null;
    }
}

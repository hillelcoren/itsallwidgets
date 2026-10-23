<?php

namespace App\Support\Export;

use RuntimeException;

/**
 * Reads one page of rows by key, cleaning each value on the way.
 */
class RowPager
{
    /**
     * The most rows one page holds.
     */
    const MAX_ROWS = 1000;

    /**
     * Stop adding rows once the encoded page passes this many bytes.
     */
    const MAX_BYTES = 4194304;

    /**
     * How many rows are read from the database at a time, unless the table
     * asks for fewer (see ExportTables::chunkSize()).
     */
    const CHUNK = 200;

    /**
     * The flags every export response is encoded with.
     */
    const JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

    /**
     * Read the page after $afterId. The query must already have its columns
     * and filters; this adds the keyset and the order.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $key
     * @param  int  $afterId
     * @param  int  $chunk  how many rows to read from the database at a time
     * @return array  ['rows' => array, 'next_after_id' => int|null, 'cleaned' => int]
     */
    public static function page($query, $key, $afterId, $chunk = self::CHUNK)
    {
        $rows = [];
        $bytes = 0;
        $cleaned = 0;
        $lastKey = $afterId;
        $exhausted = false;

        while (true) {
            $rowsRead = (clone $query)
                ->where($key, '>', $lastKey)
                ->orderBy($key)
                ->limit($chunk)
                ->get();

            foreach ($rowsRead as $row) {
                $row = static::clean((array) $row, $cleaned);
                $encoded = json_encode($row, self::JSON_FLAGS);

                if ($encoded === false) {
                    throw new RuntimeException('Could not encode a row of the export: ' . json_last_error_msg());
                }

                $rows[] = $row;
                $bytes += strlen($encoded) + 1;
                $lastKey = (int) $row[$key];

                if (count($rows) >= self::MAX_ROWS || $bytes >= self::MAX_BYTES) {
                    break 2;
                }
            }

            if (count($rowsRead) < $chunk) {
                $exhausted = true;
                break;
            }
        }

        return [
            'rows' => $rows,
            'next_after_id' => $exhausted ? null : $lastKey,
            'cleaned' => $cleaned,
        ];
    }

    /**
     * Make every string valid UTF-8 (counting the ones that weren't) and turn
     * MySQL zero dates into nulls.
     *
     * @param  array  $row
     * @param  int  $cleaned
     * @return array
     */
    public static function clean(array $row, &$cleaned)
    {
        foreach ($row as $column => $value) {
            if (! is_string($value)) {
                continue;
            }

            if (! mb_check_encoding($value, 'UTF-8')) {
                $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                $cleaned++;
            }

            $row[$column] = static::date($value);
        }

        return $row;
    }

    /**
     * Turn a zero date into null and leave anything else alone.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function date($value)
    {
        if ($value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
            return null;
        }

        return $value;
    }
}

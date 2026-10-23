<?php

namespace App\Support;

/**
 * The signature on export requests. The new app builds the same string, so
 * any change here must be made there too (the test vectors are shared).
 */
class IawExportSignature
{
    /**
     * Build the string that gets signed.
     *
     * @param  string  $method
     * @param  string  $path  the path info, e.g. /iaw-export/tables/users
     * @param  string  $query  the raw query string, without the "?"
     * @param  string  $timestamp
     * @param  string  $nonce
     * @param  string  $body
     * @return string
     */
    public static function canonical($method, $path, $query, $timestamp, $nonce, $body)
    {
        return "IAW1\n"
            . strtoupper($method) . "\n"
            . $path . "\n"
            . $query . "\n"
            . $timestamp . "\n"
            . $nonce . "\n"
            . hash('sha256', $body);
    }

    /**
     * Sign a canonical string.
     *
     * @param  string  $canonical
     * @param  string  $key
     * @return string
     */
    public static function sign($canonical, $key)
    {
        return hash_hmac('sha256', $canonical, $key);
    }
}

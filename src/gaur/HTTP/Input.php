<?php

declare(strict_types=1);

namespace Gaur\HTTP;

use Gaur\HTTP\InputRaw;

class Input
{
    /**
     * Get array value from given array
     *
     * @param mixed[] $data array to use
     * @param string  $key  field name
     *
     * @return string[]
     */
    protected static function filterArray(array $data, string $key): array
    {
        $values  = is_array($data[$key] ?? null) ? $data[$key] : [];
        $fvalues = [];

        foreach ($values as $v) {
            if (is_string($v)) {
                $fvalues[] = trim($v);
            }
        }

        return $fvalues;
    }

    /**
     * Get string value from given array
     *
     * @param mixed[] $data array to use
     * @param string  $key  field name
     *
     * @return string
     */
    protected static function filterString(array $data, string $key): string
    {
        return is_string($data[$key] ?? null) ? trim($data[$key]) : '';
    }

    /**
     * Get string value from request body
     *
     * @param string $key field name
     *
     * @return string
     */
    public static function data(string $key): string
    {
        return self::filterString(InputRaw::getData(), $key);
    }

    /**
     * Get array value from request body
     *
     * @param string $key field name
     *
     * @return string[]
     */
    public static function dataArray(string $key): array
    {
        return self::filterArray(InputRaw::getData(), $key);
    }

    /**
     * Get values from $_FILES array
     *
     * @param string $key field name
     *
     * @return mixed[][]
     */
    public static function files(string $key): array
    {
        $files = [];

        foreach ($_FILES[$key] ?? [] as $k => $finfo) {
            foreach (is_array($finfo) ? $finfo : [$finfo] as $i => $v) {
                if (!is_int($i) || is_array($v)) {
                    continue;
                }

                if (!key_exists($i, $files)) {
                    $files[$i] = [];
                }

                $files[$i][$k] = $v;
            }
        }

        return $files;
    }

    /**
     * Get string value from url
     *
     * @param string $key field name
     *
     * @return string
     */
    public static function url(string $key): string
    {
        return self::filterString($_GET, $key);
    }

    /**
     * Get array value from url
     *
     * @param string $key field name
     *
     * @return string[]
     */
    public static function urlArray(string $key): array
    {
        return self::filterArray($_GET, $key);
    }
}

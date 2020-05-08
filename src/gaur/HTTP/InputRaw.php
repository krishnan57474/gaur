<?php

declare(strict_types=1);

namespace Gaur\HTTP;

class InputRaw
{
    /**
     * Raw data from the request body
     *
     * @var mixed[]
     */
    protected static $data;

    /**
     * Parse request body data
     *
     * @return void
     */
    protected static function parseData(): void
    {
        $contentType = strtolower(
            explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0] ?? ''
        );

        if ($contentType === 'application/json') {
            $data = file_get_contents('php://input');

            if (is_string($data)) {
                self::$data = json_decode($data, true, 3);
            }
        } elseif ($contentType === 'multipart/form-data') {
            self::$data = $_POST;
        }

        if (!is_array(self::$data)) {
            self::$data = [];
        }
    }

    /**
     * Get request body data
     *
     * @return mixed[]
     */
    public static function getData(): array
    {
        if (!is_array(self::$data)) {
            self::parseData();
        }

        return self::$data;
    }
}

<?php

declare(strict_types=1);

/**
 * Get json content from data directory
 *
 * @param string $path file path of json content
 *
 * @return mixed[]
 */
function getDataContents(string $path): array
{
    $path = APPPATH . 'Data/' . $path . '.json';

    $data = json_decode(
        file_get_contents($path),
        true
    );

    return $data;
}

/**
 * Update json content of data directory
 *
 * @param string  $path file path of json content
 * @param mixed[] $data file data
 *
 * @return void
 */
function putDataContents(string $path, array $data): void
{
    $path = APPPATH . 'Data/' . $path . '.json';

    file_put_contents(
        $path,
        json_encode($data)
    );
}

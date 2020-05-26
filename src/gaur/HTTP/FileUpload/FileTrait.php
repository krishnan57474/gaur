<?php

declare(strict_types=1);

namespace Gaur\HTTP\FileUpload;

use Gaur\HTTP\Input;
use UPLOAD_ERR_OK;

trait FileTrait
{
    /**
     * Get uploaded files
     *
     * @param string $afield    attachment field name
     * @param int    $count     number of files to move (0 for all)
     * @param bool   $keepIndex preserve array keys
     *
     * @return mixed[][]
     */
    protected function getFiles(
        string $afield,
        int $count,
        bool $keepIndex
    ): array
    {
        $files     = [];
        $filesList = array_slice(
            Input::files($afield),
            0,
            $count ?: null,
            $keepIndex
        );

        foreach ($filesList as $k => $item) {
            if (!is_uploaded_file($item['tmp_name'])
                || $item['error'] !== UPLOAD_ERR_OK
                || !$item['size']
            ) {
                continue;
            }

            if ($keepIndex) {
                $files[$k] = $item;
            } else {
                $files[] = $item;
            }
        }

        return $files;
    }

    /**
     * Move uploaded file
     *
     * @param mixed[] $file file to move
     * @param string  $path destination path
     *
     * @return string
     */
    protected function moveFile(array $file, string $path): string
    {
        $fileExt  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = $this->getNewFilename($fileExt, $path);

        if (!@copy($file['tmp_name'], $path . $filename)
            && !@move_uploaded_file($file['tmp_name'], $path . $filename)
        ) {
            $filename = '';
        }

        return $filename;
    }

    /**
     * Convert unit to bytes
     *
     * @param string $unit unit with prefix
     *
     * @return float
     */
    protected function toBytes(string $unit): float
    {
        $units   = 'bkmgtpezy';
        $size    = (float)$unit;
        $uprefix = preg_replace('/[^a-zA-Z]+/', '', $unit);
        $uindex  = strpos($units, strtolower($uprefix[0] ?? 'b'));

        if (is_bool($uindex)) {
            $uindex = 0;
        }

        return $size * pow(1024, $uindex);
    }
}

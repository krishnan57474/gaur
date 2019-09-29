<?php

declare(strict_types=1);

namespace Gaur\HTTP\FileUpload;

trait FileTrait
{
    /**
     * Move uploaded file
     *
     * @param array  $file file to move
     * @param string $path destination path
     *
     * @return string
     */
    protected function moveFile(array $file, string $path): string
    {
        $fileExt  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileExt  = '.' . $fileExt;
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
        $uprefix = substr($unit, -2, 1);
        $uindex  = strpos($units, strtolower($uprefix ?: 'b'));

        if (is_bool($uindex)) {
            $uindex = 0;
        }

        return $size * pow(1024, $uindex);
    }
}

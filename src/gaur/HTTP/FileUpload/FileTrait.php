<?php

declare(strict_types=1);

namespace Gaur\HTTP\FileUpload;

trait FileTrait
{
    /**
     * Get non existing file name
     *
     * @param string $path    path to check
     * @param string $fileExt file extension
     *
     * @return string
     */
    protected function getNewFilename(string $path, string $fileExt): string
    {
        do {
            $filename = md5(uniqid((string)mt_rand(), true)) . $fileExt;
        } while (file_exists($path . $filename));

        return $filename;
    }

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
        $filename = $this->getNewFilename($path, $fileExt);

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
        $size    = (float)preg_replace('/[^\d.]/', '', $unit);
        $uprefix = preg_replace('/[\d.]/', '', $unit);
        $uprefix = strtolower($uprefix[0] ?? 'b');
        $uindex  = strpos($units, $uprefix);

        if (is_bool($uindex)) {
            $uindex = 0;
        }

        return $size * pow(1024, $uindex);
    }
}

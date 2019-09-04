<?php

declare(strict_types=1);

namespace Gaur\HTTP\FileUpload;

trait ValidateTrait
{
    /**
     * Validate file size
     *
     * @param array  $files list of files to check
     * @param string $size  maximum file size with unit prefix
     *
     * @return int
     */
    protected function validateSize(array $files, string $size): int
    {
        $eindex      = 0;
        $maxFilesize = $this->toBytes($size);

        foreach ($files as $k => $v) {
            if ($v['size'] > $maxFilesize) {
                $eindex = $k + 1;
                break;
            }
        }

        return $eindex;
    }

    /**
     * Validate file type
     *
     * @param array $files  list of files to check
     * @param array $atypes allowed file types
     *
     * @return int
     */
    protected function validateType(array $files, array $atypes): int
    {
        $eindex = 0;
        $finfo  = finfo_open(FILEINFO_MIME_TYPE);

        foreach ($files as $k => $v) {
            $fileExt = pathinfo($v['name'], PATHINFO_EXTENSION);

            if (!ctype_alnum($fileExt)) {
                $eindex = $k + 1;
                break;
            }

            $fileExt = strtolower($fileExt);

            // Validate file extension
            if (!in_array($fileExt, $atypes, true)) {
                $eindex = $k + 1;
                break;
            }

            $mimeType = finfo_file($finfo, $v['tmp_name']);
            $mimeExt  = $this->getMimeExtension($mimeType);

            // Validate mime type
            if (!in_array($fileExt, $mimeExt, true)) {
                $eindex = $k + 1;
                break;
            }
        }

        finfo_close($finfo);

        return $eindex;
    }
}

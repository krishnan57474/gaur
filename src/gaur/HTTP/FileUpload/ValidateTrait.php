<?php

declare(strict_types=1);

namespace Gaur\HTTP\FileUpload;

trait ValidateTrait
{
    /**
     * Error message
     *
     * @var string
     */
    protected string $errorMessage;

    /**
     * Validate file index
     *
     * @param mixed[][] $files     list of files to check
     * @param int       $count     number of files to move (0 for all)
     * @param bool      $keepIndex preserve array keys
     *
     * @return int
     */
    protected function validateIndex(array $files, int $count, bool $keepIndex): int
    {
        $eindex = 0;

        if (!$count
            || !$keepIndex
        ) {
            return $eindex;
        }

        foreach (array_keys($files) as $k) {
            if ($k >= $count) {
                $eindex = $k + 1;
                break;
            }
        }

        return $eindex;
    }

    /**
     * Validate file size
     *
     * @param mixed[][] $files list of files to check
     * @param string    $size  maximum file size with unit prefix
     *
     * @return int
     */
    protected function validateSize(array $files, string $size): int
    {
        $eindex      = 0;
        $maxFilesize = $this->toBytes($size);

        foreach ($files as $k => $item) {
            if ($item['size'] > $maxFilesize) {
                $eindex = $k + 1;
                break;
            }
        }

        return $eindex;
    }

    /**
     * Validate file type
     *
     * @param mixed[][] $files  list of files to check
     * @param string[]  $atypes allowed file types
     *
     * @return int
     */
    protected function validateType(array $files, array $atypes): int
    {
        $eindex = 0;
        $finfo  = finfo_open(FILEINFO_MIME_TYPE);

        foreach ($files as $k => $item) {
            $fileExt = pathinfo($item['name'], PATHINFO_EXTENSION);

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

            if (is_bool($finfo)) {
                continue;
            }

            $mimeType = finfo_file($finfo, $item['tmp_name']);
            $mimeType = is_bool($mimeType) ? '' : $mimeType;
            $mimeExt  = $this->getMimeExtension($mimeType);

            // Validate mime type
            if (!in_array($fileExt, $mimeExt, true)) {
                $eindex = $k + 1;
                break;
            }
        }

        if (!is_bool($finfo)) {
            finfo_close($finfo);
        }

        return $eindex;
    }

    /**
     * Set upload error message
     *
     * @param string $filename error filename
     * @param string $message  error message
     *
     * @return void
     */
    public function setError(string $filename, string $message): void
    {
        $this->errorMessage = 'Unable to upload the file '
            . $filename
            . '. '
            . $message;
    }
}

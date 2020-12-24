<?php

declare(strict_types=1);

namespace Gaur\HTTP;

use Gaur\HTTP\FileUpload\Errors;
use Gaur\HTTP\FileUpload\FileTrait;
use Gaur\HTTP\FileUpload\MimesTrait;
use Gaur\HTTP\FileUpload\ValidateTrait;

class FileUpload
{
    use FileTrait;
    use MimesTrait;
    use ValidateTrait;

    /**
     * Get upload error message
     *
     * @return string
     */
    public function getError(): string
    {
        $errorMessage = '';

        if (isset($this->errorMessage)) {
            $errorMessage = $this->errorMessage;
        }

        return $errorMessage;
    }

    /**
     * Get non existing filename
     *
     * @param string $fileExt file extension
     * @param string $path    path to check
     *
     * @return string
     */
    public function getNewFilename(string $fileExt, string $path): string
    {
        if ($fileExt[0] !== '.') {
            $fileExt = '.' . $fileExt;
        }

        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        do {
            $filename = md5(uniqid((string)mt_rand(), true)) . $fileExt;
        } while (file_exists($path . $filename));

        return $filename;
    }

    /**
     * Move uploaded files to new location
     *
     * $config fields
     *
     * bool     aindex  allow arbitrary array keys
     * int      count   number of files to move (0 for all)
     * bool     index   preserve array keys
     * string   name    attachment field name
     * string   path    destination path
     * string   size    maximum file size with unit prefix
     * string[] types   allowed file types
     *
     * @param mixed[] $config upload configuration
     *
     * @return string[]
     */
    public function upload(array $config): array
    {
        $this->errorMessage = '';

        if (!isset($config['aindex'])) {
            $config['aindex'] = false;
        }

        $files = $this->getFiles(
            $config['name'],
            $config['count'],
            $config['index']
        );

        if (!$files) {
            return [];
        }

        // Validate file size
        $eindex = $this->validateSize($files, $config['size']);

        if ($eindex) {
            $this->setError(
                $files[$eindex - 1]['name'],
                Errors::FILE_SIZE_EXCEED
            );
            return [];
        }

        // Validate file type
        $eindex = $this->validateType($files, $config['types']);

        if ($eindex) {
            $this->setError(
                $files[$eindex - 1]['name'],
                Errors::INVALID_FILE_TYPE
            );
            return [];
        }

        // Validate file index
        $eindex = $this->validateIndex(
            $files,
            $config['count'],
            $config['index'],
            $config['aindex']
        );

        if ($eindex) {
            $this->setError(
                $files[$eindex - 1]['name'],
                Errors::FILE_COUNT_EXCEED
            );
            return [];
        }

        $mfiles = [];

        if (substr($config['path'], -1) !== '/') {
            $config['path'] .= '/';
        }

        foreach ($files as $k => $item) {
            $filename = $this->moveFile($item, $config['path']);

            if (!$filename) {
                $this->setError(
                    $item['name'],
                    Errors::MOVE_FAILED
                );
                break;
            }

            if ($config['index']) {
                $mfiles[$k] = $filename;
            } else {
                $mfiles[] = $filename;
            }
        }

        return $mfiles;
    }
}

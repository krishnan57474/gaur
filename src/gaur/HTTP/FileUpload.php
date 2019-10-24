<?php

declare(strict_types=1);

namespace Gaur\HTTP;

use Gaur\HTTP\{
    FileUpload\Errors,
    FileUpload\FileTrait,
    FileUpload\MimesTrait,
    FileUpload\ValidateTrait,
    Input
};

class FileUpload
{
    use FileTrait;
    use MimesTrait;
    use ValidateTrait;

    /**
     * Error filename
     *
     * @var string
     */
    protected $errorFilename;

    /**
     * Error message
     *
     * @var string
     */
    protected $errorMessage;

    /**
     * Get upload error
     *
     * @return string
     */
    public function getError(): string
    {
        $error = '';

        if ($this->errorMessage) {
            $error .= 'Unable to upload the file ';
            $error .= $this->errorFilename;
            $error .= '. ';
            $error .= $this->errorMessage;
        }

        return $error;
    }

    /**
     * Get uploaded files
     *
     * @param string $afield    attachment field name
     * @param int    $count     number of files to move (0 for all)
     * @param bool   $keepIndex preserve array keys
     *
     * @return array
     */
    public function getFiles(string $afield, int $count, bool $keepIndex): array
    {
        $files     = [];
        $filesList = array_slice(
            (new Input())->files($afield),
            0,
            $count ?: null,
            $keepIndex
        );

        foreach ($filesList as $k => $v) {
            if (!is_uploaded_file($v['tmp_name'])
                || $v['error'] !== \UPLOAD_ERR_OK
                || !$v['size']
            ) {
                continue;
            }

            if ($keepIndex) {
                $files[$k] = $v;
            } else {
                $files[] = $v;
            }
        }

        return $files;
    }

    /**
     * Get non existing filename
     *
     * @param string $fileExt file extension with dot
     * @param string $path    path to check with trailing slashes
     *
     * @return string
     */
    public function getNewFilename(string $fileExt, string $path): string
    {
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
     * int      count   number of files to move (0 for all)
     * bool     index   preserve array keys
     * string   name    attachment field name
     * string   path    destination path
     * string   size    maximum file size with unit prefix
     * array    types   allowed file types
     *
     * @param array $config upload configuration
     *
     * @return array
     */
    public function upload(array $config): array
    {
        $this->errorMessage  = '';
        $this->errorFilename = '';

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
            $this->errorMessage  = Errors::FILE_SIZE_EXCEED;
            $this->errorFilename = $files[$eindex - 1]['name'];
            return [];
        }

        // Validate file type
        $eindex = $this->validateType($files, $config['types']);

        if ($eindex) {
            $this->errorMessage  = Errors::INVALID_FILE_TYPE;
            $this->errorFilename = $files[$eindex - 1]['name'];
            return [];
        }

        $mfiles = [];

        if (substr($config['path'], -1) !== '/') {
            $config['path'] .= '/';
        }

        foreach ($files as $k => $v) {
            $filename = $this->moveFile($v, $config['path']);

            if (!$filename) {
                $this->errorMessage  = Errors::MOVE_FAILED;
                $this->errorFilename = $v['name'];
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

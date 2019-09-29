<?php

declare(strict_types=1);

namespace Gaur\Controller;

use Gaur\{
    Controller\AjaxControllerTrait,
    HTTP\FileUpload,
    HTTP\UploadCache
};

trait AjaxUploadControllerTrait
{
    use AjaxControllerTrait;

    /**
     * Assemble attachment
     *
     * @param string $name   page name
     * @param string $afield attachment field name
     * @param string $dpath  destination path
     *
     * @return void
     */
    protected function assembleAttachment(
        string $name,
        string $afield,
        string $dpath
    ): void
    {
        $spath = config('Config\Paths')->writableDirectory . '/uploads/';

        $uploadCache = new UploadCache($name);
        $fileUpload  = new FileUpload();

        $this->finputs[$afield] = [];

        foreach ($uploadCache->get() as $k => $v) {
            $fileExt  = strtolower(pathinfo($v, PATHINFO_EXTENSION));
            $fileExt  = '.' . $fileExt;
            $filename = $fileUpload->getNewFilename($fileExt, $dpath);

            $this->finputs[$afield][$k] = $filename;

            rename($spath . $v, $dpath . $filename);
        }

        $uploadCache->remove();
    }

    /**
     * Remove attachment
     *
     * @param string $name page name
     *
     * @return void
     */
    protected function removeAttachment(string $name): void
    {
        $path = config('Config\Paths')->writableDirectory . '/uploads/';

        $uploadCache = new UploadCache($name);

        foreach ($uploadCache->get() as $filename) {
            unlink($path . $filename);
        }

        $uploadCache->remove();
    }

    /**
     * Validate attachment
     *
     * @param string $afield attachment field name
     * @param array  $config upload configuration
     *
     * @return bool
     */
    protected function validateAttachment(string $afield, array $config): bool
    {
        $path = config('Config\Paths')->writableDirectory . '/uploads/';

        $fileUpload = new FileUpload();

        $config['path'] = $path;

        $this->finputs[$afield] = $fileUpload->upload($config);

        $uerror = $fileUpload->getError();

        if ($uerror) {
            $this->errors[] = $uerror;
        }

        return !$this->errors;
    }
}

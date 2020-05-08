<?php

declare(strict_types=1);

namespace Gaur\Image;

use Config\Services;

class Image
{
    /**
     * Create thumbnail
     *
     * $config fields
     *
     * int     height   height of the new image
     * string  library  image library (gd, imagick)
     * string  ratio    aspect ratio dimension (auto, height, width)
     * int     width    width of the new image
     *
     * @param string  $filename source filename
     * @param string  $path     destination path
     * @param mixed[] $uconfig  user configuration
     *
     * @return bool
     */
    public function createThumb(
        string $filename,
        string $path,
        array $uconfig = []
    ): bool
    {
        $config            = [];
        $config['height']  = 256;
        $config['library'] = 'gd';
        $config['ratio']   = 'auto';
        $config['width']   = 256;

        foreach ($uconfig as $k => $v) {
            $config[$k] = $v;
        }

        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        $image = Services::image($config['library']);

        $image->withFile($filename);

        $image->resize(
            $config['width'],
            $config['height'],
            true,
            $config['ratio']
        );

        $status = $image->save(
            $path
            . basename($filename)
        );

        return $status;
    }
}

<?php

declare(strict_types=1);

namespace Gaur\Image;

use Config\Services;

class Image
{
    /**
     * Adjust canvas size to fit image
     *
     * @param string $imagePath source image path
     *
     * @return void
     */
    protected static function fitImage(string $imagePath): void
    {
        list($owidth, $oheight) = getimagesize($imagePath);

        $nwidth  = $owidth > $oheight ? $owidth : $oheight;
        $nheight = $oheight > $owidth ? $oheight : $owidth;

        if ($owidth === $nwidth
            && $oheight === $nheight
        ) {
            return;
        }

        $offsetX = 0;
        $offsetY = 0;

        if ($owidth > $oheight) {
            $offsetY = round(($owidth - $oheight) / 2);
        } else {
            $offsetX = round(($oheight - $owidth) / 2);
        }

        $fileExt = pathinfo($imagePath, PATHINFO_EXTENSION);
        $fileExt = strtolower($fileExt);

        $destImage = imagecreatetruecolor($nwidth, $nheight);

        if (!$destImage) {
            return;
        }

        if ($fileExt === 'png') {
            $sourceImage = imagecreatefrompng($imagePath);
            $black       = imagecolorallocate($destImage, 0, 0, 0);

            imagecolortransparent($destImage, $black);
        } else {
            $sourceImage = imagecreatefromjpeg($imagePath);
            $white       = imagecolorallocate($destImage, 255, 255, 255);

            imagefilledrectangle($destImage, 0, 0, $nwidth, $nheight, $white);
        }

        if (!$sourceImage) {
            imagedestroy($destImage);
            return;
        }

        imagecopy(
            $destImage,
            $sourceImage,
            (int)($offsetX ?: 0),
            (int)($offsetY ?: 0),
            0,
            0,
            $owidth,
            $oheight
        );

        if ($fileExt === 'png') {
            imagepng($destImage, $imagePath);
        } else {
            imagejpeg($destImage, $imagePath);
        }

        imagedestroy($sourceImage);
        imagedestroy($destImage);
    }

    /**
     * Create thumbnail
     *
     * $config fields
     *
     * boolean  fit      adjust canvas size to fit image
     * int      height   height of the new image
     * string   library  image library (gd, imagick)
     * string   prefix   add prefix to the filename
     * string   ratio    aspect ratio dimension (auto, height, width)
     * int      width    width of the new image
     *
     * @param string  $filename source filename
     * @param string  $path     destination path
     * @param mixed[] $uconfig  user configuration
     *
     * @return bool
     */
    public static function createThumb(
        string $filename,
        string $path,
        array $uconfig = []
    ): bool
    {
        $config            = [];
        $config['fit']     = false;
        $config['height']  = 256;
        $config['library'] = 'gd';
        $config['prefix']  = '';
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
            . $config['prefix']
            . basename($filename)
        );

        if ($status && $config['fit']) {
            self::fitImage(
                $path
                . $config['prefix']
                . basename($filename)
            );
        }

        return $status;
    }
}

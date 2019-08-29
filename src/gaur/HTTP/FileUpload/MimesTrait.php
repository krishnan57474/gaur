<?php

declare(strict_types=1);

namespace Gaur\HTTP\FileUpload;

trait MimesTrait
{
    /**
     * Mime types list
     *
     * @var array
     */
    protected $mimes = [
        'image/jpeg' => [
            'jpeg',
            'jpg'
        ],
        'image/png' => [
            'png'
        ]
    ];

    /**
     * Get extension appropiate for a mime type
     *
     * @param string $mimeType mime type
     *
     * @return array
     */
    protected function getMimeExtension(string $mimeType): array
    {
        $mimeExt = [];

        if (isset($this->mimes[$mimeType])) {
            $mimeExt = $this->mimes[$mimeType];
        }

        return $mimeExt;
    }
}

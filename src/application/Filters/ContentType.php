<?php

declare(strict_types=1);

namespace App\Filters;

use Gaur\Filters\Route;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;

class ContentType extends Route
{
    /**
     * Validate current request
     *
     * @return bool
     */
    protected function validate(): bool
    {
        $allowedContentTypes = [
            'application/json',
            'multipart/form-data'
        ];

        $contentType = strtolower(
            explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0] ?? ''
        );

        $content = $contentType === '' || in_array($contentType, $allowedContentTypes, true);

        if (!$content) {
            Response::setStatus(StatusCode::UNSUPPORTED_MEDIA_TYPE);
            Response::setJson(
                [
                    'errors' => [ 'The requested content type is not supported' ]
                ],
                false
            );
        }

        return $content;
    }
}

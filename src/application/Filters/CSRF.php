<?php

declare(strict_types=1);

namespace App\Filters;

use Gaur\Filters\Route;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;
use Gaur\Security\CSRF as FormCSRF;

class CSRF extends Route
{
    /**
     * Validate current request
     *
     * @return bool
     */
    protected function validate(): bool
    {
        $csrf   = true;
        $method = $_SERVER['REQUEST_METHOD'] ?? '';

        if ($method === 'POST' || $method === 'PUT') {
            $csrf = (new FormCSRF(self::$controller))->validate();
        }

        if (!$csrf) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson(
                [
                    'errors' => [ 'Request has expired' ]
                ],
                false
            );
        }

        return $csrf;
    }
}

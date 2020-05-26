<?php

declare(strict_types=1);

namespace App\Filters;

use Config\Services;
use Gaur\Filters\Route;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;

class AcceptType extends Route
{
    /**
     * Validate current request
     *
     * @return bool
     */
    protected function validate(): bool
    {
        $allowedAcceptTypes = [
            'application/json'
        ];

        $accept = (bool)Services::negotiator()->media($allowedAcceptTypes, true);

        if (!$accept) {
            Response::setStatus(StatusCode::NOT_ACCEPTABLE);
            Response::setJson(
                [
                    'errors' => [ 'The requested accept type is not supported' ]
                ],
                false
            );
        }

        return $accept;
    }
}

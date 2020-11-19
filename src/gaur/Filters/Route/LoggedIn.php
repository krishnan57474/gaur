<?php

declare(strict_types=1);

namespace Gaur\Filters\Route;

use Config\Services;
use Gaur\Filters\Route;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;

class LoggedIn extends Route
{
    /**
     * Validate current request
     *
     * @return bool
     */
    protected function validate(): bool
    {
        $isLoggedIn = isset($_SESSION['user_id']);

        if (!$isLoggedIn) {
            if (self::$method === 'index') {
                Response::loginRedirect(
                    Services::URI()->getPath()
                );
            } elseif (self::$method === 'api') {
                Response::setStatus(StatusCode::UNAUTHORIZED);
                Response::setJson();
            }
        }

        return $isLoggedIn;
    }
}

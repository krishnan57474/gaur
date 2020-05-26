<?php

declare(strict_types=1);

namespace App\Filters;

use Gaur\Filters\Route;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;

class NotLoggedIn extends Route
{
    /**
     * Validate current request
     *
     * @return bool
     */
    protected function validate(): bool
    {
        $isLoggedIn = isset($_SESSION['user_id']);

        if ($isLoggedIn) {
            if (self::$method === 'index') {
                Response::redirect('');
            } elseif (self::$method === 'api') {
                Response::setStatus(StatusCode::FORBIDDEN);
                Response::setJson();
            }
        }

        return !$isLoggedIn;
    }
}

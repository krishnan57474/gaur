<?php

declare(strict_types=1);

namespace App\Filters;

use Gaur\Filters\Route;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;

class Admin extends Route
{
    /**
     * Validate current request
     *
     * @return bool
     */
    protected function validate(): bool
    {
        $isAdmin = $_SESSION['is_admin'] ?? false;

        if (!$isAdmin) {
            if (self::$method === 'index') {
                Response::pageNotFound();
            } elseif (self::$method === 'api') {
                Response::setStatus(StatusCode::FORBIDDEN);
                Response::setJson();
            }
        }

        return $isAdmin;
    }
}

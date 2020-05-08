<?php

declare(strict_types=1);

namespace App\Controllers\Account;

use Gaur\Controller;
use Gaur\HTTP\Response;

class Logout extends Controller
{
    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        if (isset($_SESSION['user_id'])) {
            session_destroy();
        }

        Response::redirect('');
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Gaur\Controller;
use Gaur\HTTP\Response;

class Home extends Controller
{
    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        // Prevent non logged users
        if (!isset($_SESSION['user_id'])) {
            Response::loginRedirect('admin');
            return;
        }

        // Prevent non admin users
        if (!$_SESSION['is_admin']) {
            Response::pageNotFound();
        }

        echo view('app/admin/home');
    }
}

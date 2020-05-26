<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Gaur\Controller;

class Home extends Controller
{
    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        echo view('app/admin/home');
    }
}

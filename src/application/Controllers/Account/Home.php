<?php

declare(strict_types=1);

namespace App\Controllers\Account;

use App\Models\Users\User;
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
        $user = (new User())->get($_SESSION['user_id']);

        $data = [];

        $data['user'] = $user;

        echo view('app/default/account/home', $data);
    }
}

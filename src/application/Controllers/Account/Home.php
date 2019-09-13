<?php

declare(strict_types=1);

namespace App\Controllers\Account;

use App\Models\Users\User;
use Gaur\{
    Controller,
    HTTP\Response
};

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
            (new Response())->loginRedirect('account');
            return;
        }

        $user = (new User())->get($_SESSION['user_id']);

        if (!$user) {
            (new Response())->pageNotFound();
        }

        $data = [];

        $data['user'] = $user;

        echo view('app/default/account/home', $data);
    }
}

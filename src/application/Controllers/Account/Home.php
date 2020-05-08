<?php

declare(strict_types=1);

namespace App\Controllers\Account;

use App\Models\Users\User;
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
            Response::loginRedirect('account');
            return;
        }

        $user = (new User())->get($_SESSION['user_id']);

        $data = [];

        $data['user'] = $user;

        echo view('app/default/account/home', $data);
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers\Admin\Users;

use App\Models\Admin\Users\User;
use Gaur\Controller;
use Gaur\Controller\APIControllerTrait;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;

class View extends Controller
{
    use APIControllerTrait;

    /**
     * Default page for this controller
     *
     * @param string $id user id
     *
     * @return void
     */
    protected function index(string $id): void
    {
        // Prevent non logged users
        if (!isset($_SESSION['user_id'])) {
            Response::loginRedirect('admin/users/view/' . $id);
            return;
        }

        $id = (int)$id;

        // Prevent non admin users, invalid id
        if (!$_SESSION['is_admin']
            || $id < 1
        ) {
            Response::pageNotFound();
        }

        $user = (new User())->get($id);

        if (!$user) {
            Response::pageNotFound();
        }

        $data = [];

        $data['user'] = $user;

        echo view('app/admin/users/view', $data);
    }

    /**
     * Activate account
     *
     * @param string $id user id
     *
     * @return void
     */
    protected function activate(string $id): void
    {
        // Prevent non logged users, non admin users
        if (!$this->isLoggedIn()
            || !$this->isAdmin()
        ) {
            return;
        }

        $id   = (int)$id;
        $user = new User();

        // Prevent invalid id
        if ($id < 1
            || !$user->exists($id)
        ) {
            Response::setStatus(StatusCode::NOT_FOUND);
            Response::setJson();
            return;
        }

        $user->activate($id);

        Response::setStatus(StatusCode::OK);
        Response::setJson();
    }

    /**
     * Toggle user status
     *
     * @param string $id user id
     *
     * @return void
     */
    protected function toggleStatus(string $id): void
    {
        // Prevent non logged users, non admin users
        if (!$this->isLoggedIn()
            || !$this->isAdmin()
        ) {
            return;
        }

        $id   = (int)$id;
        $user = new User();

        // Prevent invalid id
        if ($id < 1
            || !$user->exists($id)
        ) {
            Response::setStatus(StatusCode::NOT_FOUND);
            Response::setJson();
            return;
        }

        $user->changeStatus($id);

        Response::setStatus(StatusCode::OK);
        Response::setJson();
    }
}

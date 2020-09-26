<?php

declare(strict_types=1);

namespace App\Controllers\Admin\Users;

use App\Data\Users\UserResetType;
use App\Models\Admin\Users\User;
use App\Models\Admin\Users\UserReset;
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
        $id   = (int)$id;
        $user = (new User())->get($id);

        if (!$user) {
            Response::pageNotFound();
            return;
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
        $userModel      = new User();
        $userResetModel = new UserReset();

        $id   = (int)$id;
        $user = $userModel->get($id);

        // Prevent invalid id
        if (!$user) {
            Response::setStatus(StatusCode::NOT_FOUND);
            Response::setJson();
            return;
        }

        // Prevent invalid user
        if ($user['activation']) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson();
            return;
        }

        $userResetModel->remove(
            $userResetModel->getID(
                $id,
                UserResetType::ACTIVATE_ACCOUNT
            )
        );

        $userModel->activate($id);

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
        $id   = (int)$id;
        $user = new User();

        // Prevent invalid id
        if (!$user->exists($id)) {
            Response::setStatus(StatusCode::NOT_FOUND);
            Response::setJson();
            return;
        }

        $user->changeStatus($id);

        Response::setStatus(StatusCode::OK);
        Response::setJson();
    }
}

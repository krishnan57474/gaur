<?php

declare(strict_types=1);

namespace App\Controllers\Admin\Users;

use App\Models\Admin\Users\User;
use Gaur\{
    Controller,
    Controller\AjaxControllerTrait,
    HTTP\Response
};

class View extends Controller
{
    use AjaxControllerTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        $id = $this->request->uri->getSegment(4);

        // Prevent invalid id
        if (!ctype_digit($id)
            || $id < 1
        ) {
            (new Response())->pageNotFound();
        }

        // Prevent non logged users
        if (!isset($_SESSION['user_id'])) {
            (new Response())->loginRedirect('admin/users/view/' . $id);
            return;
        }

        // Prevent non admin users
        if (!$_SESSION['is_admin']) {
            (new Response())->pageNotFound();
        }

        if ($this->isAjaxRequest()) {
            return;
        }

        $user = (new User())->get((int)$id);

        if (!$user) {
            (new Response())->pageNotFound();
        }

        $data = [];

        $data['user'] = $user;

        echo view('app/admin/users/view', $data);
    }

    /**
     * Activate account
     *
     * @param array $response ajax response
     *
     * @return void
     */
    protected function aactionActivate(array &$response): void
    {
        $id   = (int)$this->request->uri->getSegment(4);
        $user = new User();

        if (!$user->exists($id)) {
            $response['status'] = false;
            return;
        }

        $user->activate($id);
    }
}

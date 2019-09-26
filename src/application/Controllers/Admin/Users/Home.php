<?php

declare(strict_types=1);

namespace App\Controllers\Admin\Users;

use App\Data\Users\FilterConfig;
use App\Models\Admin\Users\{
    User,
    Users
};
use Gaur\{
    Controller,
    Controller\AjaxControllerTrait,
    Filters\Admin,
    HTTP\Input,
    HTTP\Response
};

class Home extends Controller
{
    use AjaxControllerTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        // Prevent non logged users
        if (!isset($_SESSION['user_id'])) {
            (new Response())->loginRedirect('admin/users');
            return;
        }

        // Prevent non admin users
        if (!$_SESSION['is_admin']) {
            (new Response())->pageNotFound();
        }

        if ($this->isAjaxRequest()) {
            return;
        }

        $filter = (new Admin())->get(__CLASS__);
        session_write_close();

        $currentPage = 1;

        if ($filter['offset']) {
            $currentPage = ($filter['offset'] / $filter['count']) + 1;
        }

        $data = [];

        $data['filter']                 = $filter;
        $data['filter']['current_page'] = $currentPage;

        $data['filterConfig'] = new FilterConfig();

        echo view('app/admin/users/home', $data);
    }

    /**
     * Toggle user status
     *
     * @param array $response ajax response
     *
     * @return void
     */
    protected function aactionChangeStatus(array &$response): void
    {
        $user = new User();
        $id   = (int)(new Input())->post('id');

        if (!$user->exists($id)) {
            $response['status'] = false;
            return;
        }

        if ($_SESSION['user_id'] === $id) {
            return;
        }

        $user->changeStatus($id);
        $response['data'] = true;
    }

    /**
     * Get users list
     *
     * @param array $response ajax response
     *
     * @return void
     */
    protected function aactionGetItems(array &$response): void
    {
        $filter = (new Admin())->filter(
            __CLASS__,
            new FilterConfig()
        );
        session_write_close();

        $items = (new Users())->filter(
            $filter['filter'],
            $filter['search'],
            $filter['count'],
            $filter['offset'],
            $filter['order']
        );

        if (!$items) {
            $response['data'] = '';
            return;
        }

        $data = [];

        $data['items'] = $items;

        $response['data'] = view(
            'app/admin/users/users_content',
            $data
        );
    }

    /**
     * Get users count
     *
     * @param array $response ajax response
     *
     * @return void
     */
    protected function aactionGetTotal(array &$response): void
    {
        $filter = (new Admin())->get(__CLASS__);
        session_write_close();

        $response['data'] = (new Users())->filterTotal(
            $filter['filter'],
            $filter['search']
        );
    }
}

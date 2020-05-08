<?php

declare(strict_types=1);

namespace App\Controllers\Admin\Users;

use App\Data\Users\FilterConfig;
use App\Models\Admin\Users\Users;
use Gaur\Controller;
use Gaur\Controller\APIControllerTrait;
use Gaur\Filters\Admin;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;

class Home extends Controller
{
    use APIControllerTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        // Prevent non logged users
        if (!isset($_SESSION['user_id'])) {
            Response::loginRedirect('admin/users');
            return;
        }

        // Prevent non admin users
        if (!$_SESSION['is_admin']) {
            Response::pageNotFound();
        }

        $filter = (new Admin(__CLASS__))->get();
        session_write_close();

        $currentPage = 1;

        if ($filter['offset']) {
            $currentPage = ($filter['offset'] / $filter['count']) + 1;
        }

        $filter['current_page'] = $currentPage;

        $data = [];

        $data['filter'] = $filter;

        $data['filterConfig'] = new FilterConfig();

        echo view('app/admin/users/home', $data);
    }

    /**
     * Get users list
     *
     * @return void
     */
    protected function getItems(): void
    {
        // Prevent non logged users, non admin users
        if (!$this->isLoggedIn()
            || !$this->isAdmin()
        ) {
            return;
        }

        $filter = (new Admin(__CLASS__))->filter(
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
            Response::setStatus(StatusCode::OK);
            Response::setJson([
                'data' => [ 'content' => '' ]
            ]);
            return;
        }

        $data = [];

        $data['items'] = $items;

        $content = view(
            'app/admin/users/users_content',
            $data
        );

        Response::setStatus(StatusCode::OK);
        Response::setJson([
            'data' => [ 'content' => $content ]
        ]);
    }

    /**
     * Get users count
     *
     * @return void
     */
    protected function getTotal(): void
    {
        // Prevent non logged users, non admin users
        if (!$this->isLoggedIn()
            || !$this->isAdmin()
        ) {
            return;
        }

        $filter = (new Admin(__CLASS__))->get();
        session_write_close();

        $total = (new Users())->filterTotal(
            $filter['filter'],
            $filter['search']
        );

        Response::setStatus(StatusCode::OK);
        Response::setJson([
            'data' => [ 'total' => $total ]
        ]);
    }
}

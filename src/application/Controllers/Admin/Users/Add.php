<?php

declare(strict_types=1);

namespace App\Controllers\Admin\Users;

use App\Controllers\Admin\Users\ValidateTrait;
use App\Models\Admin\Users\User;
use Gaur\Controller;
use Gaur\Controller\APIControllerTrait;
use Gaur\HTTP\Input;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;
use Gaur\Security\CSRF;

class Add extends Controller
{
    use APIControllerTrait;
    use ValidateTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        // Prevent non logged users
        if (!isset($_SESSION['user_id'])) {
            Response::loginRedirect('admin/users/add');
            return;
        }

        // Prevent non admin users
        if (!$_SESSION['is_admin']) {
            Response::pageNotFound();
        }

        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/admin/users/add', $data);
    }

    /**
     * Submit form
     *
     * @return void
     */
    protected function submit(): void
    {
        // Prevent invalid csrf, non logged users, non admin users
        if (!$this->isValidCsrf()
            || !$this->isLoggedIn()
            || !$this->isAdmin()
        ) {
            return;
        }

        if (!$this->validateInput()
            || !$this->validateIdentity()
            || !$this->validatePassword()
            || !$this->validateAdmin()
            || !$this->validateUser()
        ) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson([
                'errors' => $this->errors
            ]);
            return;
        }

        (new CSRF(__CLASS__))->remove();
        session_write_close();

        // Add user
        (new User())->add($this->finputs);

        $message = 'Congratulations! user has been successfully created.';

        Response::setStatus(StatusCode::CREATED);
        Response::setJson([
            'data' => [
                'message' => $message,
                'link' => 'admin/users'
            ]
        ]);
    }

    /**
     * Validate user inputs
     *
     * @return bool
     */
    protected function validateInput(): bool
    {
        $rfields = [
            'username',
            'email',
            'password',
            'password-confirm',
            'admin'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = Input::data($field);

            if ($this->finputs[$field] === '') {
                $this->errors[] = 'Please fill all required fields!';
                break;
            }
        }

        return !$this->errors;
    }
}

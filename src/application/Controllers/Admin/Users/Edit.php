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

class Edit extends Controller
{
    use APIControllerTrait;
    use ValidateTrait;

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

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/admin/users/edit', $data);
    }

    /**
     * Submit form
     *
     * @param string $id user id
     *
     * @return void
     */
    protected function submit(string $id): void
    {
        $id   = (int)$id;
        $user = new User();

        // Prevent invalid id
        if (!$user->exists($id)) {
            Response::setStatus(StatusCode::NOT_FOUND);
            Response::setJson();
            return;
        }

        if (!$this->validateInput()
            || !$this->validateIdentity()
            || !$this->validatePassword()
            || !$this->validateAdmin()
            || !$this->validateStatus()
            || !$this->validateUser($id)
        ) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson(
                [
                    'errors' => $this->errors
                ]
            );
            return;
        }

        (new CSRF(__CLASS__))->remove();
        session_write_close();

        $user->update($id, $this->finputs);

        $message = 'Congratulations! user has been successfully updated.';

        Response::setStatus(StatusCode::OK);
        Response::setJson(
            [
                'data' => [
                    'message' => $message,
                    'link' => 'admin/users'
                ]
            ]
        );
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
            'admin',
            'status'
        ];
        $ofields = [
            'password',
            'password-confirm'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = Input::data($field);

            if ($this->finputs[$field] === '') {
                $this->errors[] = 'Please fill all required fields!';
                break;
            }
        }

        foreach ($ofields as $field) {
            $this->finputs[$field] = Input::data($field);
        }

        return !$this->errors;
    }
}

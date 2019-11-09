<?php

declare(strict_types=1);

namespace App\Controllers\Admin\Users;

use App\Models\Admin\Users\User;
use Gaur\{
    Controller,
    Controller\AjaxControllerTrait,
    HTTP\Input,
    HTTP\Response,
    Security\CSRF
};

class Add extends Controller
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
            (new Response())->loginRedirect('admin/users/add');
            return;
        }

        // Prevent non admin users
        if (!$_SESSION['is_admin']) {
            (new Response())->pageNotFound();
        }

        if ($this->isAjaxRequest()) {
            return;
        }

        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/admin/users/add', $data);
    }

    /**
     * Ajax form submit
     *
     * @param array $response ajax response
     *
     * @return void
     */
    protected function aactionSubmit(array &$response): void
    {
        $csrf = new CSRF(__CLASS__);

        if (!$csrf->validate()) {
            $response['status'] = false;
            return;
        }

        if (!$this->validateInput()
            || !$this->validateUser()
        ) {
            $response['errors'] = $this->errors;
            return;
        }

        // Add user
        (new User())->add($this->finputs);

        $response['data'] = [
            'Congratulations! user has been successfully created.',
            'admin/users'
        ];

        $csrf->remove();
        session_write_close();
    }

    /**
     * Validate user inputs
     *
     * @return bool
     */
    protected function validateInput(): bool
    {
        $input   = new Input();
        $rfields = [
            'username',
            'email',
            'password',
            'password-confirm',
            'admin'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = $input->post($field);

            if ($this->finputs[$field] === '') {
                $this->errors[] = 'Please fill all required fields!';
                goto exitValidation;
            }
        }

        if (!ctype_alnum($this->finputs['username'])) {
            $this->errors[] = 'Username does not appear to be valid!';
            goto exitValidation;
        } elseif (strlen($this->finputs['username']) < 3
            || strlen($this->finputs['username']) > 32
        ) {
            $this->errors[] = 'Username must be between 3 and 32 characters!';
            goto exitValidation;
        }

        if (!filter_var($this->finputs['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Email address does not appear to be valid!';
            goto exitValidation;
        } elseif (mb_strlen($this->finputs['email']) > 128) {
            $this->errors[] = 'Email address must be less than 129 characters!';
            goto exitValidation;
        }

        if (mb_strlen($this->finputs['password']) < 4
            || mb_strlen($this->finputs['password']) > 64
        ) {
            $this->errors[] = 'Password must be between 4 and 64 characters!';
            goto exitValidation;
        }

        if ($this->finputs['password'] !== $this->finputs['password-confirm']) {
            $this->errors[] = 'Password confirmation does not match the password!';
            goto exitValidation;
        }

        if (!ctype_digit($this->finputs['admin'])
            || $this->finputs['admin'] > 1
        ) {
            $this->errors[] = 'Admin does not appear to be valid!';
            goto exitValidation;
        }

        exitValidation:
        return !$this->errors;
    }

    /**
     * Validate username and email
     *
     * @return bool
     */
    protected function validateUser(): bool
    {
        $user = new User();

        // Normalize input
        $this->finputs['username'] = strtolower($this->finputs['username']);
        $this->finputs['email']    = mb_strtolower($this->finputs['email']);

        if ($user->isUsernameExists($this->finputs['username'])) {
            $this->errors[] = 'Username is already in use!';
        } elseif ($user->isEmailExists($this->finputs['email'])) {
            $this->errors[] = 'Email address is already in use!';
        }

        return !$this->errors;
    }
}

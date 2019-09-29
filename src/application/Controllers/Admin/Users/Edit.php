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

class Edit extends Controller
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
            (new Response())->loginRedirect('admin/users/edit/' . $id);
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

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/admin/users/edit', $data);
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

        $user = new User();
        $id   = (int)$this->request->uri->getSegment(4);

        if (!$user->exists($id)) {
            $response['status'] = false;
            return;
        }

        if (!$this->validateInput()
            || !$this->validateUser()
        ) {
            $response['errors'] = $this->errors;
            return;
        }

        // Update user
        $user->update($id, $this->finputs);

        $response['data'] = [
            'Congratulations! user has been successfully updated.',
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
            'admin',
            'status'
        ];
        $ofields = [
            'password',
            'password-confirm'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = $input->post($field);

            if ($this->finputs[$field] === '') {
                $this->errors[] = 'Please fill all required fields!';
                goto exitValidation;
            }
        }

        foreach ($ofields as $field) {
            $this->finputs[$field] = $input->post($field);
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
        } elseif (mb_strlen($this->finputs['email']) > 254) {
            $this->errors[] = 'Email address must be less than 255 characters!';
            goto exitValidation;
        }

        if ($this->finputs['password'] !== ''
            && (mb_strlen($this->finputs['password']) < 4
            || mb_strlen($this->finputs['password']) > 64)
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

        if (!ctype_digit($this->finputs['status'])
            || $this->finputs['status'] > 1
        ) {
            $this->errors[] = 'Status does not appear to be valid!';
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
        $user     = new User();
        $id       = (int)$this->request->uri->getSegment(4);
        $userInfo = $user->get($id);

        // Normalize input
        $this->finputs['username'] = strtolower($this->finputs['username']);
        $this->finputs['email']    = mb_strtolower($this->finputs['email']);

        if ($this->finputs['username'] !== $userInfo['username']
            && $user->isUsernameExists($this->finputs['username'])
        ) {
            $this->errors[] = 'Username is already in use!';
        } elseif ($this->finputs['email'] !== $userInfo['email']
            && $user->isEmailExists($this->finputs['email'])
        ) {
            $this->errors[] = 'Email address is already in use!';
        }

        return !$this->errors;
    }
}

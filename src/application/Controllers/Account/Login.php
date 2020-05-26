<?php

declare(strict_types=1);

namespace App\Controllers\Account;

use App\Models\Users\User;
use Config\Services;
use Gaur\Controller;
use Gaur\Controller\APIControllerTrait;
use Gaur\HTTP\Input;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;
use Gaur\Security\CSRF;

class Login extends Controller
{
    use APIControllerTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        // Keep login redirect
        Services::session()->keepFlashdata('login_redirect');

        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/default/account/login', $data);
    }

    /**
     * Submit form
     *
     * @return void
     */
    protected function submit(): void
    {
        if (!$this->validateInput()
            || !$this->validateIdentity()
            || !$this->validateUser()
        ) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson(
                [
                    'errors' => $this->errors
                ]
            );
            return;
        }

        // Default login success redirect
        $url = $_SESSION['login_redirect'] ?? 'account';

        unset($_SESSION['login_redirect']);
        (new CSRF(__CLASS__))->remove();
        session_write_close();

        (new User())->updateLastVisit($_SESSION['user_id']);

        $message = 'You have successfully logged in';

        Response::setStatus(StatusCode::OK);
        Response::setJson(
            [
                'data' => [
                    'message' => $message,
                    'link' => $url
                ]
            ]
        );
    }

    /**
     * Validate identity
     *
     * @return bool
     */
    protected function validateIdentity(): bool
    {
        if ($this->finputs['username'] !== ''
            && (strlen($this->finputs['username']) < 3
            || strlen($this->finputs['username']) > 32)
        ) {
            $this->errors[] = 'Incorrect login!';
        } elseif ($this->finputs['email'] !== ''
            && mb_strlen($this->finputs['email']) > 128
        ) {
            $this->errors[] = 'Incorrect login!';
        }

        return !$this->errors;
    }

    /**
     * Validate user inputs
     *
     * @return bool
     */
    protected function validateInput(): bool
    {
        $rfields = [
            'identity',
            'password'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = Input::data($field);

            if ($this->finputs[$field] === '') {
                $this->errors[] = 'Please fill all required fields!';
                goto exitValidation;
            }
        }

        $this->finputs['username'] = '';
        $this->finputs['email']    = '';

        if (ctype_alnum($this->finputs['identity'])) {
            $this->finputs['username'] = strtolower($this->finputs['identity']);
        } elseif (filter_var($this->finputs['identity'], FILTER_VALIDATE_EMAIL)) {
            $this->finputs['email'] = mb_strtolower($this->finputs['identity']);
        } else {
            $this->errors[] = 'Incorrect login!';
            goto exitValidation;
        }

        if (mb_strlen($this->finputs['password']) < 4
            || mb_strlen($this->finputs['password']) > 64
        ) {
            $this->errors[] = 'Incorrect login!';
            goto exitValidation;
        }

        exitValidation:
        return !$this->errors;
    }

    /**
     * Validate identity and password
     *
     * @return bool
     */
    protected function validateUser(): bool
    {
        $user = null;

        if ($this->finputs['username'] !== '') {
            $user = (new User())->getByUsername($this->finputs['username']);
        } elseif ($this->finputs['email'] !== '') {
            $user = (new User())->getByEmail($this->finputs['email']);
        }

        if (!$user
            || !$user['status']
            || !$user['activation']
            || !password_verify($this->finputs['password'], $user['password'])
        ) {
            $this->errors[] = 'Incorrect login!';
        } else {
            // Store user id
            $_SESSION['user_id'] = $user['id'];

            // Admin check
            $_SESSION['is_admin'] = (bool)$user['admin'];
        }

        return !$this->errors;
    }
}

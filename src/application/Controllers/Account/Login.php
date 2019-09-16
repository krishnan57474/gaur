<?php

declare(strict_types=1);

namespace App\Controllers\Account;

use App\Models\Users\User;
use Gaur\{
    Controller,
    Controller\AjaxControllerTrait,
    HTTP\Input,
    HTTP\Response,
    Security\CSRF
};

class Login extends Controller
{
    use AjaxControllerTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        // Prevent logged users
        if (isset($_SESSION['user_id'])) {
            (new Response())->redirect('');
            return;
        }

        // Keep login redirect
        session()->keepFlashdata('login_redirect');

        if ($this->isAjaxRequest()) {
            return;
        }

        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF())->create(__CLASS__, 60);
        session_write_close();

        echo view('app/default/account/login', $data);
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
        $csrf = new CSRF();

        if (!$csrf->validate(__CLASS__)) {
            $response['status'] = false;
            return;
        }

        if (!$this->validateInput()
            || !$this->validateIdentity()
            || !$this->validateUser()
        ) {
            $response['errors'] = $this->errors;
            return;
        }

        (new User())->updateLastVisit($_SESSION['user_id']);

        // Default login success redirect
        $url = $_SESSION['login_redirect'] ?? 'account';

        $response['data'] = [
            'You have successfully logged in',
            $url
        ];

        unset($_SESSION['login_redirect']);
        $csrf->remove(__CLASS__);
        session_write_close();
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
            && mb_strlen($this->finputs['email']) > 254
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
        $input   = new Input();
        $rfields = [
            'identity',
            'password'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = $input->post($field);

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

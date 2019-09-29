<?php

declare(strict_types=1);

namespace App\Controllers\Account\Password;

use App\Models\Users\User;
use Gaur\{
    Controller,
    Controller\AjaxControllerTrait,
    HTTP\Input,
    HTTP\Response,
    Security\CSRF
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
            (new Response())->loginRedirect('account/password');
            return;
        }

        if ($this->isAjaxRequest()) {
            return;
        }

        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/default/account/password/home', $data);
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
            || !$this->validatePassword()
        ) {
            $response['errors'] = $this->errors;
            return;
        }

        (new User())->updatePassword(
            $_SESSION['user_id'],
            $this->finputs['password-new']
        );

        $response['data'] = 'Congratulations! your password has been successfully updated.';

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
            'password-current',
            'password-new',
            'password-confirm'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = $input->post($field);

            if ($this->finputs[$field] === '') {
                $this->errors[] = 'Please fill all required fields!';
                goto exitValidation;
            }
        }

        if (mb_strlen($this->finputs['password-new']) < 4
            || mb_strlen($this->finputs['password-new']) > 64
        ) {
            $this->errors[] = 'Password must be between 4 and 64 characters!';
            goto exitValidation;
        }

        if ($this->finputs['password-new'] !== $this->finputs['password-confirm']) {
            $this->errors[] = 'Password confirmation does not match the password!';
            goto exitValidation;
        }

        exitValidation:
        return !$this->errors;
    }

    /**
     * Validate password
     *
     * @return bool
     */
    protected function validatePassword(): bool
    {
        if (mb_strlen($this->finputs['password-current']) < 4
            || mb_strlen($this->finputs['password-current']) > 64
        ) {
            $this->errors[] = 'Incorrect current password!';
            goto exitValidation;
        }

        $password = (new User())->getPassword($_SESSION['user_id']);

        if (!password_verify($this->finputs['password-current'], $password)) {
            $this->errors[] = 'Incorrect current password!';
            goto exitValidation;
        } elseif ($this->finputs['password-current'] === $this->finputs['password-new']) {
            $this->errors[] = 'Please use different password!';
            goto exitValidation;
        }

        exitValidation:
        return !$this->errors;
    }
}

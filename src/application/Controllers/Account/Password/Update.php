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

class Update extends Controller
{
    use AjaxControllerTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        // Prevent unauthorized access
        if (!isset($_SESSION['password_update'])) {
            (new Response())->redirect('');
            return;
        }

        // Keep verification
        session()->keepFlashdata('password_update');

        if ($this->isAjaxRequest()) {
            return;
        }

        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/default/account/password/update', $data);
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

        if (!$this->validateInput()) {
            $response['errors'] = $this->errors;
            return;
        }

        (new User())->updatePassword(
            $_SESSION['password_update'],
            $this->finputs['password']
        );

        $response['data'] = [
            'Congratulations! your password has been successfully updated. Now you can log in by using your new password.',
            'account/login'
        ];

        unset($_SESSION['password_update']);
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

        exitValidation:
        return !$this->errors;
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers\Account\Password;

use App\Models\Users\User;
use Gaur\Controller;
use Gaur\Controller\APIControllerTrait;
use Gaur\HTTP\Input;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;
use Gaur\Security\CSRF;

class Update extends Controller
{
    use APIControllerTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        // Prevent unauthorized access
        if (!isset($_SESSION['password_update'])) {
            Response::redirect('');
            return;
        }

        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/default/account/password/update', $data);
    }

    /**
     * Submit form
     *
     * @return void
     */
    protected function submit(): void
    {
        // Prevent unauthorized access
        if (!isset($_SESSION['password_update'])) {
            Response::setStatus(StatusCode::UNAUTHORIZED);
            Response::setJson();
            return;
        }

        // Prevent invalid csrf
        if (!$this->isValidCsrf()) {
            return;
        }

        if (!$this->validateInput()) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson([
                'errors' => $this->errors
            ]);
            return;
        }

        $uid = $_SESSION['password_update'];

        unset($_SESSION['password_update']);
        session()->removeTempdata('password_update');
        (new CSRF(__CLASS__))->remove();
        session_write_close();

        (new User())->updatePassword(
            $uid,
            $this->finputs['password']
        );

        $message = 'Congratulations! your password has been successfully updated. Now you can log in by using your new password.';

        Response::setStatus(StatusCode::OK);
        Response::setJson([
            'data' => [
                'message' => $message,
                'link' => 'account/login'
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
            'password',
            'password-confirm'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = Input::data($field);

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

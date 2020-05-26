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
        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/default/account/password/home', $data);
    }

    /**
     * Submit form
     *
     * @return void
     */
    protected function submit(): void
    {
        if (!$this->validateInput()
            || !$this->validatePassword()
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

        (new User())->updatePassword(
            $_SESSION['user_id'],
            $this->finputs['password-new']
        );

        $message = 'Congratulations! your password has been successfully updated.';

        Response::setStatus(StatusCode::OK);
        Response::setJson(
            [
                'data' => [ 'message' => $message ]
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
            'password-current',
            'password-new',
            'password-confirm'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = Input::data($field);

            if ($this->finputs[$field] === '') {
                $this->errors[] = 'Please fill all required fields!';
                goto exitValidation;
            }
        }

        if (mb_strlen($this->finputs['password-current']) < 4
            || mb_strlen($this->finputs['password-current']) > 64
        ) {
            $this->errors[] = 'Incorrect current password!';
            goto exitValidation;
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
        $password = (new User())->getPassword($_SESSION['user_id']);

        if (!password_verify($this->finputs['password-current'], $password)) {
            $this->errors[] = 'Incorrect current password!';
        } elseif ($this->finputs['password-current'] === $this->finputs['password-new']) {
            $this->errors[] = 'Please use different password!';
        }

        return !$this->errors;
    }
}

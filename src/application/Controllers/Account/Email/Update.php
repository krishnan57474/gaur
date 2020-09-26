<?php

declare(strict_types=1);

namespace App\Controllers\Account\Email;

use App\Models\Users\User;
use Config\Services;
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
        if (!isset($_SESSION['email_update'])) {
            Response::redirect('');
            return;
        }

        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/default/account/email/update', $data);
    }

    /**
     * Submit form
     *
     * @return void
     */
    protected function submit(): void
    {
        // Prevent unauthorized access
        if (!isset($_SESSION['email_update'])) {
            Response::setStatus(StatusCode::UNAUTHORIZED);
            Response::setJson();
            return;
        }

        if (!$this->validateInput()
            || !$this->validateEmail()
        ) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson(
                [ 'errors' => $this->errors ]
            );
            return;
        }

        $email = $_SESSION['email_update']['email'];

        unset($_SESSION['email_update']);
        Services::session()->removeTempdata('email_update');
        (new CSRF(__CLASS__))->remove();
        session_write_close();

        (new User())->updateEmail(
            $_SESSION['user_id'],
            $email
        );

        $message = 'Congratulations! your email address has been successfully updated.';

        Response::setStatus(StatusCode::OK);
        Response::setJson(
            [
                'data' => [
                    'message' => $message,
                    'link'    => 'account'
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
        $this->finputs['code'] = Input::data('code');

        if ($this->finputs['code'] === '') {
            $this->errors[] = 'Please fill all required fields!';
            goto exitValidation;
        }

        if (!ctype_alnum($this->finputs['code'])
            || strlen($this->finputs['code']) !== 8
            || $_SESSION['email_update']['code'] !== $this->finputs['code']
        ) {
            $this->errors[] = 'Invalid verification code or expired verification code.';
            goto exitValidation;
        }

        exitValidation:
        return !$this->errors;
    }

    /**
     * Validate email address
     *
     * @return bool
     */
    protected function validateEmail(): bool
    {
        $email = $_SESSION['email_update']['email'];

        if ((new User())->isEmailExists($email)) {
            $this->errors[] = 'Email address is already in use!';
        }

        return !$this->errors;
    }
}

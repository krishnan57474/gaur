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
use Gaur\Mail\Mail;
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

        echo view('app/default/account/email/home', $data);
    }

    /**
     * Submit form
     *
     * @return void
     */
    protected function submit(): void
    {
        if (!$this->validateInput()
            || !$this->validateEmail()
        ) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson(
                [ 'errors' => $this->errors ]
            );
            return;
        }

        $token = substr((string)mt_rand(), 0, 6);

        // Email address update verification
        $_SESSION['email_update'] = [
            'email' => $this->finputs['email'],
            'code'  => $token
        ];

        // 60 minutes
        Services::session()->markAsTempdata('email_update', 3600);

        (new CSRF(__CLASS__))->remove();
        session_write_close();

        $this->sendMail($token);

        $message = 'A confirmation has been sent to the provided email address.';

        Response::setStatus(StatusCode::OK);
        Response::setJson(
            [
                'data' => [
                    'message' => $message,
                    'link'    => 'account/email/update'
                ]
            ]
        );
    }

    /**
     * Send email
     *
     * @param string $token random token
     *
     * @return bool
     */
    protected function sendMail(string $token): bool
    {
        helper('xhtml');

        $data = [
            'to'       => $this->finputs['email'],
            'subject'  => 'Email address update request',
            'token'    => $token
        ];

        $status = Mail::send(
            'email/default/account/email/update',
            $data
        );

        return $status;
    }

    /**
     * Validate user inputs
     *
     * @return bool
     */
    protected function validateInput(): bool
    {
        $this->finputs['email'] = Input::data('email');

        if ($this->finputs['email'] === '') {
            $this->errors[] = 'Please fill all required fields!';
            goto exitValidation;
        }

        if (!filter_var($this->finputs['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Email address does not appear to be valid!';
            goto exitValidation;
        } elseif (mb_strlen($this->finputs['email']) > 128) {
            $this->errors[] = 'Email address must be less than 129 characters!';
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
        // Normalize input
        $this->finputs['email'] = mb_strtolower($this->finputs['email']);

        if ((new User())->isEmailExists($this->finputs['email'])) {
            $this->errors[] = 'Email address is already in use!';
        }

        return !$this->errors;
    }
}

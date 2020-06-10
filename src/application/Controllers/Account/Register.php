<?php

declare(strict_types=1);

namespace App\Controllers\Account;

use App\Data\Users\UserResetType;
use App\Models\Users\User;
use App\Models\Users\UserReset;
use Gaur\Controller;
use Gaur\Controller\APIControllerTrait;
use Gaur\HTTP\Input;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;
use Gaur\Mail\Mail;
use Gaur\Security\CSRF;

class Register extends Controller
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

        echo view('app/default/account/register', $data);
    }

    /**
     * Submit form
     *
     * @return void
     */
    protected function submit(): void
    {
        if (!$this->validateInput()
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

        (new CSRF(__CLASS__))->remove();
        session_write_close();

        $this->finputs['password'] = md5(uniqid((string)mt_rand(), true));

        $uid = (new User())->add($this->finputs);

        $token = bin2hex(random_bytes(128));

        $this->addVerification($uid, md5($token));

        $this->sendMail($token);

        $message = [
            'Congratulations! your new account has been successfully created.',
            'A confirmation has been sent to the provided email address. You will need to follow the instructions in that message in order to gain access to the site.'
        ];

        Response::setStatus(StatusCode::CREATED);
        Response::setJson(
            [
                'data' => [ 'message' => $message ]
            ]
        );
    }

    /**
     * Add account verification
     *
     * @param int    $uid   user id
     * @param string $token random token
     *
     * @return void
     */
    protected function addVerification(int $uid, string $token): void
    {
        $data = [
            'uid'    => $uid,
            'token'  => $token,
            'type'   => UserResetType::ACTIVATE_ACCOUNT,
            'expire' => null
        ];

        (new UserReset())->add($data);
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
            'subject'  => 'Activate your account',
            'username' => $this->finputs['username'],
            'token'    => $token
        ];

        $status = Mail::send(
            'email/default/account/email/activate',
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
        $rfields = [
            'username',
            'email'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = Input::data($field);

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

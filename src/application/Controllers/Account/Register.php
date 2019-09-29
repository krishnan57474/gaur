<?php

declare(strict_types=1);

namespace App\Controllers\Account;

use App\Data\Users\UserResetType;
use App\Models\Users\{
    User,
    UserReset
};
use Gaur\{
    Controller,
    Controller\AjaxControllerTrait,
    HTTP\Input,
    HTTP\Response,
    Mail\Mail,
    Security\CSRF
};

class Register extends Controller
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

        if ($this->isAjaxRequest()) {
            return;
        }

        $data = [];

        // 60 minutes
        $data['csrf'] = (new CSRF(__CLASS__))->create(60);
        session_write_close();

        echo view('app/default/account/register', $data);
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
            || !$this->validateUser()
        ) {
            $response['errors'] = $this->errors;
            return;
        }

        // Random password
        $this->finputs['password'] = md5(uniqid((string)mt_rand(), true));

        // Add user
        $uid = (new User())->add($this->finputs);

        // Generate random token
        $token = md5(uniqid((string)mt_rand(), true));

        // Add account verification
        $this->addVerification($uid, $token);

        // Send email verification
        $this->sendMail($token);

        $response['data'] = [
            'Congratulations! your new account has been successfully created.',
            'A confirmation has been sent to the provided email address. You will need to follow the instructions in that message in order to gain access to the site.'
        ];

        $csrf->remove();
        session_write_close();
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
            'type'   => UserResetType::ACCOUNT_ACTIVATION,
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

        $status = (new Mail())->send(
            'email/default/account/email/activation',
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
        $input   = new Input();
        $rfields = [
            'username',
            'email'
        ];

        foreach ($rfields as $field) {
            $this->finputs[$field] = $input->post($field);

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
        } elseif (mb_strlen($this->finputs['email']) > 254) {
            $this->errors[] = 'Email address must be less than 255 characters!';
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

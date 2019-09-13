<?php

declare(strict_types=1);

namespace App\Controllers\Account\Password;

use App\Models\Users\{
    User,
    UserReset
};
use App\Data\Users\UserResetType;
use Gaur\{
    Controller,
    Controller\AjaxControllerTrait,
    HTTP\Input,
    HTTP\Response,
    Mail\Mail,
    Security\CSRF
};

class Forgot extends Controller
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
        $data['csrf'] = (new CSRF())->create(__CLASS__, 60);
        session_write_close();

        echo view('app/default/account/password/forgot', $data);
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

        // Generate random token
        $token = md5(uniqid((string)mt_rand(), true));

        // Add account verification
        $this->addVerification($this->finputs['uid'], $token);

        // Send password reset
        if ($this->sendMail($this->finputs['username'], $token)) {
            $response['data'] = 'Congratulations! a password reset has been sent.';
        } else {
            $response['errors'] = [
                'Oops! something went wrong please try again later'
            ];
        }

        $csrf->remove(__CLASS__);
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
            'uid'     => $uid,
            'token'   => $token,
            'type'    => UserResetType::PASSWORD_RESET,
            'expire'  => date('Y-m-d H:i:s', strtotime('1 hour'))
        ];

        (new UserReset())->add($data);
    }

    /**
     * Send email
     *
     * @param string $username username
     * @param string $token    random token
     *
     * @return bool
     */
    protected function sendMail(string $username, string $token): bool
    {
        $data = [
            'to'       => $this->finputs['email'],
            'subject'  => 'Password reset request',
            'username' => $username,
            'token'    => $token
        ];

        $status = (new Mail())->send(
            'email/default/account/password/forgot',
            $data
        );

        return $status;
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
            $this->errors[] = 'Unable to sent confirmation!';
        } elseif ($this->finputs['email'] !== ''
            && mb_strlen($this->finputs['email']) > 254
        ) {
            $this->errors[] = 'Unable to sent confirmation!';
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
        $this->finputs['identity'] = (new Input())->post('identity');

        if ($this->finputs['identity'] === '') {
            $this->errors[] = 'Please fill all required fields!';
            goto exitValidation;
        }

        $this->finputs['username'] = '';
        $this->finputs['email']    = '';

        if (ctype_alnum($this->finputs['identity'])) {
            $this->finputs['username'] = strtolower($this->finputs['identity']);
        } elseif (filter_var($this->finputs['identity'], FILTER_VALIDATE_EMAIL)) {
            $this->finputs['email'] = mb_strtolower($this->finputs['identity']);
        } else {
            $this->errors[] = 'Unable to sent confirmation!';
            goto exitValidation;
        }

        exitValidation:
        return !$this->errors;
    }

    /**
     * Validate identity
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
        ) {
            $this->errors[] = 'Unable to sent confirmation!';
        } else {
            $this->finputs['uid']      = $user['id'];
            $this->finputs['username'] = $user['username'];
            $this->finputs['email']    = $user['email'];
        }

        return !$this->errors;
    }
}

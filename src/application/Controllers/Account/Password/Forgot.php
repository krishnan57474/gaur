<?php

declare(strict_types=1);

namespace App\Controllers\Account\Password;

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

class Forgot extends Controller
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

        echo view('app/default/account/password/forgot', $data);
    }

    /**
     * Submit form
     *
     * @return void
     */
    protected function submit(): void
    {
        if (!$this->validateInput()
            || !$this->validateIdentity()
            || !$this->validateUser()
        ) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson(
                [ 'errors' => $this->errors ]
            );
            return;
        }

        (new CSRF(__CLASS__))->remove();
        session_write_close();

        $token = bin2hex(random_bytes(128));

        $this->addReset($this->finputs['uid'], md5($token));

        $message = 'Congratulations! a password reset has been sent.';

        if ($this->sendMail($token)) {
            Response::setStatus(StatusCode::OK);
            Response::setJson(
                [
                    'data' => [ 'message' => $message ]
                ]
            );
        } else {
            Response::setStatus(StatusCode::INTERNAL_SERVER_ERROR);
            Response::setJson();
        }
    }

    /**
     * Add password reset
     *
     * @param int    $uid   user id
     * @param string $token random token
     *
     * @return void
     */
    protected function addReset(int $uid, string $token): void
    {
        $data = [
            'uid'     => $uid,
            'token'   => $token,
            'type'    => UserResetType::RESET_PASSWORD,
            'expire'  => date('Y-m-d H:i:s', strtotime('1 hour'))
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
            'subject'  => 'Password reset request',
            'username' => $this->finputs['username'],
            'token'    => $token
        ];

        $status = Mail::send(
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
            && mb_strlen($this->finputs['email']) > 128
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
        $this->finputs['identity'] = Input::data('identity');

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

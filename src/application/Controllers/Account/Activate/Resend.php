<?php

declare(strict_types=1);

namespace App\Controllers\Account\Activate;

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

class Resend extends Controller
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

        echo view('app/default/account/activate/resend', $data);
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

        $this->updateVerification($this->finputs['uid'], md5($token));

        $message = 'Congratulations! an activation has been sent.';

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
     * Update account verification
     *
     * @param int    $uid   user id
     * @param string $token random token
     *
     * @return void
     */
    protected function updateVerification(int $uid, string $token): void
    {
        $userResetModel = new UserReset();

        $rid = $userResetModel->getID(
            $uid,
            UserResetType::ACTIVATE_ACCOUNT
        );

        $userResetModel->update($rid, $token);
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
            $this->errors[] = 'Unable to sent activation!';
        } elseif ($this->finputs['email'] !== ''
            && mb_strlen($this->finputs['email']) > 128
        ) {
            $this->errors[] = 'Unable to sent activation!';
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
            $this->errors[] = 'Unable to sent activation!';
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
            || $user['activation']
        ) {
            $this->errors[] = 'Unable to sent activation!';
        } else {
            $this->finputs['uid']      = $user['id'];
            $this->finputs['username'] = $user['username'];
            $this->finputs['email']    = $user['email'];
        }

        return !$this->errors;
    }
}

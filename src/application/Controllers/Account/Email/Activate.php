<?php

declare(strict_types=1);

namespace App\Controllers\Account\Email;

use App\Data\Users\UserResetType;
use App\Models\Users\User;
use App\Models\Users\UserReset;
use Gaur\Controller;
use Gaur\Controller\APIControllerTrait;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;

class Activate extends Controller
{
    use APIControllerTrait;

    /**
     * Default page for this controller
     *
     * @param string $token random token
     *
     * @return void
     */
    protected function index(string $token): void
    {
        // Prevent logged users
        if (isset($_SESSION['user_id'])) {
            Response::redirect('');
            return;
        }

        $data = [];

        $data['token'] = $token;

        echo view('app/default/account/email/activate', $data);
    }

    /**
     * Verify account activation
     *
     * @param string $token random token
     *
     * @return void
     */
    protected function verify(string $token): void
    {
        // Prevent logged users
        if (!$this->isNotLoggedIn()) {
            return;
        }

        $userReset = new UserReset();
        $reset     = $userReset->get(md5($token));

        if (!$reset
            || $reset['type'] != UserResetType::ACTIVATE_ACCOUNT
        ) {
            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson([
                'errors' => [ 'Oops! verification failed. Invalid verification code or expired verification code.' ]
            ]);
            return;
        }

        $userReset->remove($reset['id']);

        // Activate user
        (new User())->activate($reset['uid']);

        // Password create verification
        $_SESSION['password_create'] = $reset['uid'];

        // 60 minutes
        session()->markAsTempdata('password_create', 3600);
        session_write_close();

        $message = 'Congratulations! your email address has been successfully verified.';

        Response::setStatus(StatusCode::OK);
        Response::setJson([
            'data' => [
                'message' => $message,
                'link' => 'account/password/create'
            ]
        ]);
    }
}

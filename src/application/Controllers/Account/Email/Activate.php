<?php

declare(strict_types=1);

namespace App\Controllers\Account\Email;

use App\Data\Users\UserResetType;
use App\Models\Users\User;
use App\Models\Users\UserReset;
use Config\Services;
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
        $userReset = new UserReset();
        $reset     = $userReset->get(md5($token));

        if (!$reset
            || $reset['type'] != UserResetType::ACTIVATE_ACCOUNT
        ) {
            $errorMessage = 'Oops! verification failed. Invalid verification code or expired verification code.';

            Response::setStatus(StatusCode::BAD_REQUEST);
            Response::setJson(
                [
                    'errors' => [ $errorMessage ]
                ]
            );
            return;
        }

        // Password create verification
        $_SESSION['password_create'] = $reset['uid'];

        // 60 minutes
        Services::session()->markAsTempdata('password_create', 3600);
        session_write_close();

        $userReset->remove($reset['id']);

        (new User())->activate($reset['uid']);

        $message = 'Congratulations! your email address has been successfully verified.';

        Response::setStatus(StatusCode::OK);
        Response::setJson(
            [
                'data' => [
                    'message' => $message,
                    'link' => 'account/password/create'
                ]
            ]
        );
    }
}

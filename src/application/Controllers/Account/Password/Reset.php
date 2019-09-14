<?php

declare(strict_types=1);

namespace App\Controllers\Account\Password;

use App\Data\Users\UserResetType;
use App\Models\Users\UserReset;
use Gaur\{
    Controller,
    Controller\AjaxControllerTrait,
    HTTP\Response
};

class Reset extends Controller
{
    use AjaxControllerTrait;

    /**
     * Default page for this controller
     *
     * @return void
     */
    protected function index(): void
    {
        $token = $this->request->uri->getSegment(4);

        // Prevent logged users and invalid token
        if (isset($_SESSION['user_id'])
            || !ctype_alnum($token)
            || strlen($token) !== 32
        ) {
            (new Response())->redirect('');
            return;
        }

        if ($this->isAjaxRequest()) {
            return;
        }

        echo view('app/default/account/password/reset');
    }

    /**
     * Validate password reset token
     *
     * @param array $response ajax response
     *
     * @return void
     */
    protected function aactionValidate(array &$response): void
    {
        $userReset = new UserReset();
        $token     = $this->request->uri->getSegment(4);
        $reset     = $userReset->get(
            $token,
            UserResetType::PASSWORD_RESET
        );

        if (!$reset
            || strtotime($reset['expire']) < time()
        ) {
            if ($reset) {
                $userReset->remove($reset['id']);
            }

            $response['errors'] = [
                'Oops! verification failed. Invalid verification code or expired verification code.'
            ];
            return;
        }

        $userReset->remove($reset['id']);

        // Password update verification
        $_SESSION['password_update'] = $reset['uid'];
        $this->session->markAsFlashdata('password_update');
        session_write_close();

        $response['data'] = [
            'Congratulations! Your password reset request has been successfully verified.',
            'account/password/update'
        ];
    }
}

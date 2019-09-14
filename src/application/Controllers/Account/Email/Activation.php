<?php

declare(strict_types=1);

namespace App\Controllers\Account\Email;

use App\Data\Users\UserResetType;
use App\Models\Users\{
    User,
    UserReset
};
use Gaur\{
    Controller,
    Controller\AjaxControllerTrait,
    HTTP\Response
};

class Activation extends Controller
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

        echo view('app/default/account/email/activation');
    }

    /**
     * Validate account activation token
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
            UserResetType::ACCOUNT_ACTIVATION
        );

        if (!$reset) {
            $response['errors'] = [
                'Oops! verification failed. Invalid verification code or expired verification code.'
            ];
            return;
        }

        $userReset->remove($reset['id']);

        // Activate user
        (new User())->activate($reset['uid']);

        // Password create verification
        $_SESSION['password_create'] = $reset['uid'];
        $this->session->markAsFlashdata('password_create');
        session_write_close();

        $response['data'] = [
            'Congratulations! your email address has been successfully verified.',
            'account/password/create'
        ];
    }
}

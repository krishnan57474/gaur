<?php

declare(strict_types=1);

namespace Gaur\HTTP;

use CodeIgniter\Exceptions\PageNotFoundException;

class Response
{
    /**
     * Redirect to new location after login
     *
     * @param string $uri uri to redirect
     *
     * @return void
     */
    public function loginRedirect(string $uri): void
    {
        $_SESSION['login_redirect'] = $uri;
        session()->markAsFlashdata('login_redirect');
        session_write_close();

        $this->redirect('account/login');
    }

    /**
     * Set page not found
     *
     * @return void
     * @throws PageNotFoundException
     */
    public function pageNotFound(): void
    {
        throw PageNotFoundException::forPageNotFound();
    }

    /**
     * Redirect to new location
     *
     * @param string $uri uri to redirect
     *
     * @return void
     */
    public function redirect(string $uri): void
    {
        service('response')->redirect(config('Config\App')->baseURL . $uri);
    }
}

<?php

declare(strict_types=1);

namespace Gaur\HTTP;

use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Services;

class Response
{
    /**
     * Redirect to new location after login
     *
     * @param string $uri uri to redirect
     *
     * @return void
     */
    public static function loginRedirect(string $uri): void
    {
        $_SESSION['login_redirect'] = $uri;
        Services::session()->markAsFlashdata('login_redirect');
        session_write_close();

        self::redirect('account/login');
    }

    /**
     * Set page not found
     *
     * @return void
     * @throws PageNotFoundException
     */
    public static function pageNotFound(): void
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
    public static function redirect(string $uri): void
    {
        Services::Response()->redirect(
            config('Config\App')->baseURL . $uri
        );
    }

    /**
     * Set response body as json
     *
     * @param mixed[] $data output
     * @param bool    $send send output
     *
     * @return void
     */
    public static function setJson(array $data = [], bool $send = true): void
    {
        Services::Response()->setHeader('Content-Type', 'application/json; charset=UTF-8')->setBody($data ? json_encode($data) : '');

        if ($send) {
            Services::Response()->send();
        }
    }

    /**
     * Set response status
     *
     * @param int $code status code
     *
     * @return void
     */
    public static function setStatus(int $code): void
    {
        Services::Response()->setStatusCode($code);
    }
}

<?php

declare(strict_types=1);

namespace Gaur\Security;

use Gaur\HTTP\Input;

class CSRF
{
    /**
     * Create random csrf token
     *
     * @param string $name page name
     * @param int    $time token expiration time in minutes
     *
     * @return array
     */
    public function create(string $name, int $time = 0): array
    {
        $name = 'csrf-' . $name;

        $token = [
            'name' => bin2hex(random_bytes(32)),
            'hash' => bin2hex(random_bytes(32))
        ];

        $_SESSION[$name] = [
            $token['name'],
            $token['hash']
        ];

        if ($time) {
            session()->markAsTempdata($name, $time * 60);
        } else {
            session()->markAsFlashdata($name);
        }

        return $token;
    }

    /**
     * Remove csrf token from session
     *
     * @param string $name page name
     *
     * @return void
     */
    public function remove(string $name): void
    {
        $name = 'csrf-' . $name;
        unset($_SESSION[$name]);
        session()->removeTempdata($name);
    }

    /**
     * Validate csrf token
     *
     * @param string $name page name
     *
     * @return bool
     */
    public function validate(string $name): bool
    {
        $name   = 'csrf-' . $name;
        $stoken = $_SESSION[$name][1] ?? null;
        $ptoken = '';

        if (isset($_SESSION[$name][0])) {
            $ptoken = (new Input())->post($_SESSION[$name][0]);
        }

        return ($stoken === $ptoken);
    }
}

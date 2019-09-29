<?php

declare(strict_types=1);

namespace Gaur\Security;

use Gaur\HTTP\Input;

class CSRF
{
    /**
     * Page name
     *
     * @var string
     */
    protected $name;

    /**
     * Load initial values
     *
     * @param string $name page name
     *
     * @return void
     */
    public function __construct(string $name)
    {
        $this->name = 'csrf-' . $name;
    }

    /**
     * Create random csrf token
     *
     * @param int $time token expiration time in minutes
     *
     * @return array
     */
    public function create(int $time = 0): array
    {
        $token = [
            'name' => bin2hex(random_bytes(32)),
            'hash' => bin2hex(random_bytes(32))
        ];

        $_SESSION[$this->name] = [
            $token['name'],
            $token['hash']
        ];

        if ($time) {
            session()->markAsTempdata($this->name, $time * 60);
        } else {
            session()->markAsFlashdata($this->name);
        }

        return $token;
    }

    /**
     * Remove csrf token from session
     *
     * @return void
     */
    public function remove(): void
    {
        unset($_SESSION[$this->name]);
        session()->removeTempdata($this->name);
    }

    /**
     * Validate csrf token
     *
     * @return bool
     */
    public function validate(): bool
    {
        $stoken = $_SESSION[$this->name][1] ?? null;
        $ptoken = '';

        if (isset($_SESSION[$this->name][0])) {
            $ptoken = (new Input())->post($_SESSION[$this->name][0]);
        }

        return ($stoken === $ptoken);
    }
}

<?php

declare(strict_types=1);

namespace Gaur\Security;

class ContentSecurityPolicy
{
    /**
     * CSP nonce
     *
     * @var string
     */
    protected static $nonce;

    /**
     * Get random CSP nonce
     *
     * @return string
     */
    public static function get(): string
    {
        if (!self::$nonce) {
            self::$nonce = bin2hex(random_bytes(32));
        }

        return self::$nonce;
    }
}

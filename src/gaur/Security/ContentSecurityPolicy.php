<?php

declare(strict_types=1);

namespace Gaur\Security;

use ArrayObject;
use Gaur\Security\ContentSecurityPolicy\Config;

class ContentSecurityPolicy
{
    /**
     * CSP nonce
     *
     * @var string
     */
    protected static $nonce;

    /**
     * Get CSP
     *
     * @param Config $config csp config
     *
     * @return string
     */
    public static function get(Config $config): string
    {
        $csp = '';

        foreach (new ArrayObject($config) as $k => $v) {
            if (!$v) {
                continue;
            }

            $k = preg_replace('/[A-Z]/', '-$0', (string)$k) ?: '';

            $csp .= strtolower($k);
            $csp .= ' ';
            $csp .= implode(' ', $v);
            $csp .= '; ';
        }

        return rtrim($csp);
    }

    /**
     * Get random CSP nonce
     *
     * @return string
     */
    public static function getNonce(): string
    {
        if (!is_string(self::$nonce)) {
            self::$nonce = bin2hex(random_bytes(32));
        }

        return self::$nonce;
    }
}

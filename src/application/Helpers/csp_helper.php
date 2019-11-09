<?php

declare(strict_types=1);

use Gaur\Security\ContentSecurityPolicy;

/**
 * Get content security policy
 *
 * @param string $cspConfig   csp config class name
 * @param bool   $allowScript allow script tag to execute
 * @param bool   $allowStyle  allow style tag to execute
 *
 * @return string
 */
function getCsp(
    string $cspConfig,
    bool $allowScript = false,
    bool $allowStyle = false
): string
{
    $config = new $cspConfig();
    $csp    = new ContentSecurityPolicy();
    $nonce  = $csp->getNonce();

    if ($allowScript) {
        $config->scriptSrc   = $config->scriptSrc ?? [];
        $config->scriptSrc[] = '\'nonce-' . $nonce . '\'';
    }

    if ($allowStyle) {
        $config->styleSrc   = $config->styleSrc ?? [];
        $config->styleSrc[] = '\'nonce-' . $nonce . '\'';
    }

    return $csp->get($config);
}

/**
 * Get content security policy nonce
 *
 * @return string
 */
function getCspNonce(): string
{
    $csp   = new ContentSecurityPolicy();
    $nonce = $csp->getNonce();

    return $nonce;
}

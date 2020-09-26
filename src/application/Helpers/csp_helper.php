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
    $cspConfig = 'App\Data\Security\ContentSecurityPolicy\\' . $cspConfig;
    $config    = new $cspConfig();
    $nonce     = ContentSecurityPolicy::getNonce();

    if ($allowScript) {
        $config->scriptSrc[] = '\'nonce-' . $nonce . '\'';
    }

    if ($allowStyle) {
        $config->styleSrc[] = '\'nonce-' . $nonce . '\'';
    }

    return ContentSecurityPolicy::get($config);
}

/**
 * Get content security policy nonce
 *
 * @return string
 */
function getCspNonce(): string
{
    $nonce = ContentSecurityPolicy::getNonce();

    return $nonce;
}

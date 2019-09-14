<?php

declare(strict_types=1);

use App\Data\Security\CSPConfig;
use Gaur\Security\ContentSecurityPolicy;

/**
 * Get content security policy
 *
 * @return string
 */
function getCsp(): string
{
    $config = new CSPConfig();
    $csp    = new ContentSecurityPolicy();
    $nonce  = $csp->getNonce();

    $config->scriptSrc   = $config->scriptSrc ?? [];
    $config->scriptSrc[] = '\'nonce-' . $nonce . '\'';

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

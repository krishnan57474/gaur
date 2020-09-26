<?php

declare(strict_types=1);

namespace App\Data\Security\ContentSecurityPolicy;

use Gaur\Security\ContentSecurityPolicy\Config as CspConfig;

class Config extends CspConfig
{
    /**
     * Valid sources of AJAX, WebSocket
     *
     * @var string[]
     */
    public array $connectSrc = [
        '\'self\''
    ];

    /**
     * Default policy for loading content
     *
     * @var string[]
     */
    public array $defaultSrc = [
        '\'none\''
    ];

    /**
     * Valid sources of fonts
     *
     * @var string[]
     */
    public array $fontSrc = [
        'https://cdn.jsdelivr.net'
    ];

    /**
     * Valid sources of images
     *
     * @var string[]
     */
    public array $imgSrc = [
        '\'self\'',
        'https://cdn.jsdelivr.net'
    ];

    /**
     * Valid sources of javaScripts
     *
     * @var string[]
     */
    public array $scriptSrc = [
        '\'self\'',
        'https://cdn.jsdelivr.net'
    ];

    /**
     * Valid sources of stylesheets
     *
     * @var string[]
     */
    public array $styleSrc = [
        '\'self\'',
        'https://cdn.jsdelivr.net'
    ];
}

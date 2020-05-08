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
    public $connectSrc = [
        '\'self\''
    ];

    /**
     * Default policy for loading content
     *
     * @var string[]
     */
    public $defaultSrc = [
        '\'none\''
    ];

    /**
     * Valid sources of fonts
     *
     * @var string[]
     */
    public $fontSrc = [
        'https://cdn.jsdelivr.net'
    ];

    /**
     * Valid sources of images
     *
     * @var string[]
     */
    public $imgSrc = [
        '\'self\'',
        'https://cdn.jsdelivr.net'
    ];

    /**
     * Valid sources of javaScripts
     *
     * @var string[]
     */
    public $scriptSrc = [
        '\'self\'',
        'https://cdn.jsdelivr.net'
    ];

    /**
     * Valid sources of stylesheets
     *
     * @var string[]
     */
    public $styleSrc = [
        '\'self\'',
        'https://cdn.jsdelivr.net'
    ];
}

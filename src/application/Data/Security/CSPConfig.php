<?php

declare(strict_types=1);

namespace App\Data\Security;

use Gaur\Security\ContentSecurityPolicy\Config;

class CSPConfig extends Config
{
    /**
     * Valid sources of AJAX, WebSocket
     *
     * @var array
     */
    public $connectSrc = [
        '\'self\''
    ];

    /**
     * Default policy for loading content
     *
     * @var array
     */
    public $defaultSrc = [
        '\'none\''
    ];

    /**
     * Valid sources of fonts
     *
     * @var array
     */
    public $fontSrc = [
        'https://cdn.jsdelivr.net'
    ];

    /**
     * Valid sources of images
     *
     * @var array
     */
    public $imgSrc = [
        '\'self\''
    ];

    /**
     * Valid sources of javaScripts
     *
     * @var array
     */
    public $scriptSrc = [
        '\'self\'',
        'https://cdn.jsdelivr.net'
    ];

    /**
     * Valid sources of stylesheets
     *
     * @var array
     */
    public $styleSrc = [
        '\'self\'',
        'https://cdn.jsdelivr.net'
    ];
}

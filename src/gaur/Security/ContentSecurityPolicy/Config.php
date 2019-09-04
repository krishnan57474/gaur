<?php

declare(strict_types=1);

namespace Gaur\Security\ContentSecurityPolicy;

class Config
{
    /**
     * Valid sources of web workers, nested browsing contexts
     *
     * @var array
     */
    public $childSrc = [];

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
     * Valid sources of form action
     *
     * @var array
     */
    public $formAction = [];

    /**
     * Valid sources of images
     *
     * @var array
     */
    public $imgSrc = [
        '\'self\''
    ];

    /**
     * Valid sources of audio, video
     *
     * @var array
     */
    public $mediaSrc = [];

    /**
     * Valid sources of plugins
     *
     * @var array
     */
    public $objectSrc = [];

    /**
     * Valid MIME types for plugins
     *
     * @var array
     */
    public $pluginTypes = [];

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

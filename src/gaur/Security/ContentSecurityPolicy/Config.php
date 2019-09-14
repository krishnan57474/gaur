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
    public $connectSrc = [];

    /**
     * Default policy for loading content
     *
     * @var array
     */
    public $defaultSrc = [];

    /**
     * Valid sources of fonts
     *
     * @var array
     */
    public $fontSrc = [];

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
    public $imgSrc = [];

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
    public $scriptSrc = [];

    /**
     * Valid sources of stylesheets
     *
     * @var array
     */
    public $styleSrc = [];
}

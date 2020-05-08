<?php

declare(strict_types=1);

namespace Gaur\Security\ContentSecurityPolicy;

class Config
{
    /**
     * Valid sources of web workers, nested browsing contexts
     *
     * @var string[]
     */
    public $childSrc = [];

    /**
     * Valid sources of AJAX, WebSocket
     *
     * @var string[]
     */
    public $connectSrc = [];

    /**
     * Default policy for loading content
     *
     * @var string[]
     */
    public $defaultSrc = [];

    /**
     * Valid sources of fonts
     *
     * @var string[]
     */
    public $fontSrc = [];

    /**
     * Valid sources of form action
     *
     * @var string[]
     */
    public $formAction = [];

    /**
     * Valid sources of images
     *
     * @var string[]
     */
    public $imgSrc = [];

    /**
     * Valid sources of audio, video
     *
     * @var string[]
     */
    public $mediaSrc = [];

    /**
     * Valid sources of plugins
     *
     * @var string[]
     */
    public $objectSrc = [];

    /**
     * Valid MIME types for plugins
     *
     * @var string[]
     */
    public $pluginTypes = [];

    /**
     * Valid sources of javaScripts
     *
     * @var string[]
     */
    public $scriptSrc = [];

    /**
     * Valid sources of stylesheets
     *
     * @var string[]
     */
    public $styleSrc = [];
}

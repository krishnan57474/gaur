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
    public array $childSrc = [];

    /**
     * Valid sources of AJAX, WebSocket
     *
     * @var string[]
     */
    public array $connectSrc = [];

    /**
     * Default policy for loading content
     *
     * @var string[]
     */
    public array $defaultSrc = [];

    /**
     * Valid sources of fonts
     *
     * @var string[]
     */
    public array $fontSrc = [];

    /**
     * Valid sources of form action
     *
     * @var string[]
     */
    public array $formAction = [];

    /**
     * Valid sources of images
     *
     * @var string[]
     */
    public array $imgSrc = [];

    /**
     * Valid sources of audio, video
     *
     * @var string[]
     */
    public array $mediaSrc = [];

    /**
     * Valid sources of plugins
     *
     * @var string[]
     */
    public array $objectSrc = [];

    /**
     * Valid MIME types for plugins
     *
     * @var string[]
     */
    public array $pluginTypes = [];

    /**
     * Valid sources of javaScripts
     *
     * @var string[]
     */
    public array $scriptSrc = [];

    /**
     * Valid sources of stylesheets
     *
     * @var string[]
     */
    public array $styleSrc = [];
}

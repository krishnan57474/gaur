<?php

declare(strict_types=1);

namespace Gaur\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

abstract class Route implements FilterInterface
{
    /**
     * Controller name
     *
     * @var string
     */
    protected static $controller;

    /**
     * Method name
     *
     * @var string
     */
    protected static $method;

    /**
     * Validate current request
     *
     * @return bool
     */
    abstract protected function validate(): bool;

    /**
     * Load initial values
     *
     * @return void
     */
    protected function init(): void
    {
        $routes     = Services::routes()->getRoutes();
        $uri        = Services::URI()->getPath();
        $route      = '';
        $controller = '';
        $method     = '';

        foreach ($routes as $path => $className) {
            if (preg_match('#^' . $path . '$#', $uri)) {
                $route = ltrim($className, '\\');
                break;
            }
        }

        list($controller, $method) = explode('::', $route);
        list($method)              = explode('/', $method);

        self::$controller = $controller;
        self::$method     = $method;
    }

    /**
     * Load initial values
     *
     * @return void
     */
    public function __construct()
    {
        if (!self::$controller) {
            Services::session();
            $this->init();
        }
    }

    /**
     * Filter current request
     *
     * @param RequestInterface  $request  request instance
     * @param ResponseInterface $response response instance
     *
     * @return void
     */
    public function after(
        RequestInterface $request,
        ResponseInterface $response
    ): void
    {
    }

    /**
     * Filter current request
     *
     * @param RequestInterface $request request instance
     *
     * @return ResponseInterface|null
     */
    public function before(RequestInterface $request): ?ResponseInterface
    {
        $response = null;

        if (!$this->validate()) {
            $response = Services::Response();
        }

        return $response;
    }
}

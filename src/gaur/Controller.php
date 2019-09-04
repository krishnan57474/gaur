<?php

declare(strict_types=1);

namespace Gaur;

use CodeIgniter\{
    Controller as CIController,
    HTTP\RequestInterface,
    HTTP\ResponseInterface
};
use Config\Services;
use Psr\Log\LoggerInterface;

abstract class Controller extends CIController
{
    /**
     * Helpers to be loaded automatically
     *
     * @var array
     */
    protected $helpers = [
        'csp',
        'html'
    ];

    /**
     * Session object
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $session;

    /**
     * Default page for this controller
     *
     * @return void
     */
    abstract protected function index(): void;

    /**
     * Remap method calls
     *
     * @param string ...$args list of arguments
     *
     * @return void
     */
    public function _remap(string ...$args): void
    {
        $this->index(...$args);
    }

    /**
     * Initialize controller
     *
     * @param RequestInterface  $request  request object
     * @param ResponseInterface $response response object
     * @param LoggerInterface   $logger   logger object
     *
     * @return void
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    )
    {
        parent::initController($request, $response, $logger);

        $this->session = Services::session();
    }
}

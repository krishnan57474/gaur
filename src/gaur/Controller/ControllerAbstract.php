<?php

declare(strict_types=1);

namespace Gaur\Controller;

use CodeIgniter\{
    Controller,
    HTTP\RequestInterface,
    HTTP\ResponseInterface
};
use Config\Services;
use Psr\Log\LoggerInterface;

abstract class ControllerAbstract extends Controller
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

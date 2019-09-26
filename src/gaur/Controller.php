<?php

declare(strict_types=1);

namespace Gaur;

use CodeIgniter\Controller as BaseController;
use Config\Services;

abstract class Controller extends BaseController
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
        Services::session();

        $this->index(...$args);
    }
}

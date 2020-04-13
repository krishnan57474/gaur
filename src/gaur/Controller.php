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
    protected $preloadHelpers = [];

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

        $helpers = [
            'csp',
            'html'
        ];

        helper(array_merge($helpers, $this->preloadHelpers));

        $this->index(...$args);
    }
}

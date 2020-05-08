<?php

declare(strict_types=1);

namespace Gaur;

use CodeIgniter\Controller as BaseController;
use Config\Services;
use Gaur\HTTP\Response;
use Gaur\HTTP\StatusCode;

class Controller extends BaseController
{
    /**
     * Helpers to be loaded automatically
     *
     * @var string[]
     */
    protected $preloadHelpers = [];

    /**
     * Remap method calls
     *
     * @param string $method  method in the controller gets called
     * @param string ...$args list of arguments
     *
     * @return void
     */
    public function _remap(string $method, string ...$args): void
    {
        Services::session();

        $helpers = [
            'csp',
            'html'
        ];

        helper(array_merge($helpers, $this->preloadHelpers));

        if (method_exists($this, $method)) {
            $this->{$method}(...$args);
        } else {
            Response::setStatus(StatusCode::INTERNAL_SERVER_ERROR);
            Response::setJson();
        }
    }
}

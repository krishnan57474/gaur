<?php

declare(strict_types=1);

namespace Gaur;

use CodeIgniter\Controller as BaseController;
use Config\Services;

class Controller extends BaseController
{
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
            'html'
        ];

        if ($method === 'index') {
            $helpers[] = 'csp';
        }

        helper($helpers);

        $this->$method(...$args);
    }
}

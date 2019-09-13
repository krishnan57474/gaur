<?php

declare(strict_types=1);

namespace Gaur;

use Gaur\Controller\ControllerAbstract;

abstract class Controller extends ControllerAbstract
{
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
}

<?php

declare(strict_types=1);

namespace Gaur\Controller;

trait APIControllerTrait
{
    /**
     * Input errors
     *
     * @var string[]
     */
    protected array $errors;

    /**
     * Filtered inputs
     *
     * @var mixed[]
     */
    protected array $finputs;

    /**
     * Handle api request
     *
     * @param string $method  method in the controller gets called
     * @param string ...$args list of arguments
     *
     * @return void
     */
    protected function api(string $method, string ...$args): void
    {
        $this->errors  = [];
        $this->finputs = [];

        $this->$method(...$args);
    }
}

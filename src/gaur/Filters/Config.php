<?php

declare(strict_types=1);

namespace Gaur\Filters;

class Config
{
    /**
     * Allowed filter fields
     *
     * @var array<string, string>
     */
    public $filterFields = [];

    /**
     * Allowed filter values
     *
     * @var array<string, string[]>
     */
    public $filterValues = [];

    /**
     * Allowed order fields
     *
     * @var array<string, string>
     */
    public $orderFields = [];

    /**
     * Allowed search fields
     *
     * @var array<string, string>
     */
    public $searchFields = [];
}

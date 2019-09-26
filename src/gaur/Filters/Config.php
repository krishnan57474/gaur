<?php

declare(strict_types=1);

namespace Gaur\Filters;

class Config
{
    /**
     * Allowed filter fields
     *
     * @var array
     */
    public $filterFields = [];

    /**
     * Allowed filter values
     *
     * @var array
     */
    public $filterValues = [];

    /**
     * Allowed order fields
     *
     * @var array
     */
    public $orderFields = [];

    /**
     * Allowed search fields
     *
     * @var array
     */
    public $searchFields = [];
}

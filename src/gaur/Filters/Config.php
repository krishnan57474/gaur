<?php

declare(strict_types=1);

namespace Gaur\Filters;

class Config
{
    /**
     * Allowed filter fields
     *
     * @var string[]
     */
    public $filterFields = [];

    /**
     * Allowed filter values
     *
     * @var string[][]
     */
    public $filterValues = [];

    /**
     * Allowed order fields
     *
     * @var string[]
     */
    public $orderFields = [];

    /**
     * Allowed search fields
     *
     * @var string[]
     */
    public $searchFields = [];
}

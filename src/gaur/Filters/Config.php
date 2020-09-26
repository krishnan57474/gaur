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
    public array $filterFields = [];

    /**
     * Allowed filter values
     *
     * @var array<string, string[]>
     */
    public array $filterValues = [];

    /**
     * Allowed order fields
     *
     * @var array<string, string>
     */
    public array $orderFields = [];

    /**
     * Allowed search fields
     *
     * @var array<string, string>
     */
    public array $searchFields = [];
}

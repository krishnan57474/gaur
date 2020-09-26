<?php

declare(strict_types=1);

namespace App\Data\Users;

use Gaur\Filters\Config;

class FilterConfig extends Config
{
    /**
     * Allowed filter fields
     *
     * @var array<string, string>
     */
    public array $filterFields = [
        'status'     => 'Status',
        'activation' => 'Activation',
        'admin'      => 'Admin'
    ];

    /**
     * Allowed filter values
     *
     * @var array<string, string[]>
     */
    public array $filterValues = [
        'status' => [
            'Disabled',
            'Enabled'
        ],
        'activation' => [
            'Unverified',
            'Verified'
        ],
        'admin' => [
            'No',
            'Yes'
        ]
    ];

    /**
     * Allowed order fields
     *
     * @var array<string, string>
     */
    public array $orderFields = [
        'id'           => 'ID',
        'last_visited' => 'Last visited',
        'status'       => 'Status'
    ];

    /**
     * Allowed search fields
     *
     * @var array<string, string>
     */
    public array $searchFields = [
        'id'       => 'ID',
        'username' => 'Username',
        'email'    => 'Email'
    ];
}

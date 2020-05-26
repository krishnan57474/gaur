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
    public $filterFields = [
        'status'     => 'Status',
        'activation' => 'Activation',
        'admin'      => 'Admin'
    ];

    /**
     * Allowed filter values
     *
     * @var array<string, string[]>
     */
    public $filterValues = [
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
    public $orderFields = [
        'id'           => 'ID',
        'last_visited' => 'Last Visited',
        'status'       => 'Status'
    ];

    /**
     * Allowed search fields
     *
     * @var array<string, string>
     */
    public $searchFields = [
        'id'       => 'ID',
        'username' => 'Username',
        'email'    => 'Email'
    ];
}

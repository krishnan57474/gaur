<?php

declare(strict_types=1);

namespace App\Data\Users;

use Gaur\Filters\Config;

class FilterConfig extends Config
{
    /**
     * Allowed filter fields
     *
     * @var array
     */
    public $filterFields = [
        'status'     => 'Status',
        'activation' => 'Activation',
        'admin'      => 'Admin'
    ];

    /**
     * Allowed filter values
     *
     * @var array
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
     * Allowed search fields
     *
     * @var array
     */
    public $searchFields = [
        'id'       => 'ID',
        'username' => 'Username',
        'email'    => 'Email'
    ];

    /**
     * Allowed order fields
     *
     * @var array
     */
    public $orderFields = [
        'id'           => 'ID',
        'username'     => 'Username',
        'email'        => 'Email',
        'last_visited' => 'Last Visited',
        'status'       => 'Status'
    ];
}
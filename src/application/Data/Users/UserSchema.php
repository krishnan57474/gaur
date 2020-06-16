<?php

declare(strict_types=1);

namespace App\Data\Users;

use Gaur\Database\SchemaFilter;
use Gaur\Database\SchemaType;

class UserSchema extends SchemaFilter
{
    /**
     * id
     *
     * @var array<string, mixed>
     */
    public $id = [
        'null' => false,
        'type' => SchemaType::INT
    ];

    /**
     * username
     *
     * @var array<string, mixed>
     */
    public $username = [
        'null' => false,
        'type' => SchemaType::VARCHAR
    ];

    /**
     * email
     *
     * @var array<string, mixed>
     */
    public $email = [
        'null' => false,
        'type' => SchemaType::VARCHAR
    ];

    /**
     * password
     *
     * @var array<string, mixed>
     */
    public $password = [
        'null' => false,
        'type' => SchemaType::VARCHAR
    ];

    /**
     * status
     *
     * @var array<string, mixed>
     */
    public $status = [
        'null' => false,
        'type' => SchemaType::TINYINT
    ];

    /**
     * activation
     *
     * @var array<string, mixed>
     */
    public $activation = [
        'null' => false,
        'type' => SchemaType::TINYINT
    ];

    /**
     * admin
     *
     * @var array<string, mixed>
     */
    public $admin = [
        'null' => false,
        'type' => SchemaType::TINYINT
    ];

    /**
     * date added
     *
     * @var array<string, mixed>
     */
    public $date_added = [
        'null' => false,
        'type' => SchemaType::DATETIME
    ];

    /**
     * last visited
     *
     * @var array<string, mixed>
     */
    public $last_visited = [
        'null' => true,
        'type' => SchemaType::DATETIME
    ];

    /**
     * total
     *
     * @var array<string, mixed>
     */
    public $total = [
        'null' => false,
        'type' => SchemaType::INT
    ];
}

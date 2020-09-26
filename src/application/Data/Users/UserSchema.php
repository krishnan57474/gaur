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
    public array $id = [
        'null' => false,
        'type' => SchemaType::INT
    ];

    /**
     * username
     *
     * @var array<string, mixed>
     */
    public array $username = [
        'null' => false,
        'type' => SchemaType::VARCHAR
    ];

    /**
     * email address
     *
     * @var array<string, mixed>
     */
    public array $email = [
        'null' => false,
        'type' => SchemaType::VARCHAR
    ];

    /**
     * password
     *
     * @var array<string, mixed>
     */
    public array $password = [
        'null' => false,
        'type' => SchemaType::VARCHAR
    ];

    /**
     * status
     *
     * @var array<string, mixed>
     */
    public array $status = [
        'null' => false,
        'type' => SchemaType::TINYINT
    ];

    /**
     * activation
     *
     * @var array<string, mixed>
     */
    public array $activation = [
        'null' => false,
        'type' => SchemaType::TINYINT
    ];

    /**
     * admin
     *
     * @var array<string, mixed>
     */
    public array $admin = [
        'null' => false,
        'type' => SchemaType::TINYINT
    ];

    /**
     * date added
     *
     * @var array<string, mixed>
     */
    public array $date_added = [
        'null' => false,
        'type' => SchemaType::DATETIME
    ];

    /**
     * last visited
     *
     * @var array<string, mixed>
     */
    public array $last_visited = [
        'null' => true,
        'type' => SchemaType::DATETIME
    ];
}

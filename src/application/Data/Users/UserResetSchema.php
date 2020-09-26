<?php

declare(strict_types=1);

namespace App\Data\Users;

use Gaur\Database\SchemaFilter;
use Gaur\Database\SchemaType;

class UserResetSchema extends SchemaFilter
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
     * user id
     *
     * @var array<string, mixed>
     */
    public array $uid = [
        'null' => false,
        'type' => SchemaType::INT
    ];

    /**
     * token
     *
     * @var array<string, mixed>
     */
    public array $token = [
        'null' => false,
        'type' => SchemaType::CHAR
    ];

    /**
     * type
     *
     * @var array<string, mixed>
     */
    public array $type = [
        'null' => false,
        'type' => SchemaType::TINYINT
    ];

    /**
     * expire
     *
     * @var array<string, mixed>
     */
    public array $expire = [
        'null' => true,
        'type' => SchemaType::DATETIME
    ];
}

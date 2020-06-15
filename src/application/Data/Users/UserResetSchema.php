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
    public $id = [
        'null' => false,
        'type' => SchemaType::INT
    ];

    /**
     * user id
     *
     * @var array<string, mixed>
     */
    public $uid = [
        'null' => false,
        'type' => SchemaType::INT
    ];

    /**
     * token
     *
     * @var array<string, mixed>
     */
    public $token = [
        'null' => false,
        'type' => SchemaType::CHAR
    ];

    /**
     * type
     *
     * @var array<string, mixed>
     */
    public $type = [
        'null' => false,
        'type' => SchemaType::TINYINT
    ];

    /**
     * expire
     *
     * @var array<string, mixed>
     */
    public $expire = [
        'null' => true,
        'type' => SchemaType::DATETIME
    ];
}

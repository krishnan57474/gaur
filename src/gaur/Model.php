<?php

declare(strict_types=1);

namespace Gaur;

use CodeIgniter\Database\BaseConnection;
use Config\Database;

class Model
{
    /**
     * Database instance
     *
     * @var BaseConnection
     */
    protected BaseConnection $db;

    /**
     * Initialize model
     *
     * @param string $group database connection group
     *
     * @return void
     */
    public function __construct(string $group = '')
    {
        $this->db = Database::connect($group ?: null);
    }
}

<?php

declare(strict_types=1);

namespace Gaur;

class Model
{
    /**
     * Database object
     *
     * @var \CodeIgniter\Database\BaseConnection
     */
    protected $db;

    /**
     * Initialize model
     *
     * @param string $group database connection group
     *
     * @return void
     */
    public function __construct(string $group = null)
    {
        $this->db = db_connect($group);
    }
}

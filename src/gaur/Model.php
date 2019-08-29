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
     * @return void
     */
    public function __construct()
    {
        $this->db = db_connect();
    }
}

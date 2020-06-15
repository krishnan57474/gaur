<?php

declare(strict_types=1);

namespace App\Models\Admin\Users;

use App\Data\Users\UserSchema;
use Gaur\Model;

class User extends Model
{
    /**
     * Activate user account
     *
     * @param int $id user id
     *
     * @return void
     */
    public function activate(int $id): void
    {
        $qry = 'UPDATE `' . $this->db->prefixTable('users') . '`
                SET `status` = 1,
                    `activation` = 1
                WHERE `id` = ' . $id;

        $this->db->query($qry);
    }

    /**
     * Add user
     *
     * @param mixed[] $rdata user information
     *
     * @return void
     */
    public function add(array $rdata): void
    {
        $data = (new UserSchema())->filter($rdata);

        $qry = 'INSERT INTO `' . $this->db->prefixTable('users') . '`(`username`, `email`, `password`, `status`, `activation`, `admin`, `date_added`)
                VALUES ('
                    . $this->db->escape($data['username'])
                    . ', ' . $this->db->escape($data['email'])
                    . ', ' . $this->db->escape(password_hash($data['password'], PASSWORD_DEFAULT))
                    . ', 1'
                    . ', 1'
                    . ', ' . $data['admin']
                    . ', ' . $this->db->escape(date('Y-m-d H:i:s'))
                . ')';

        $this->db->query($qry);
    }

    /**
     * Update user status
     *
     * @param int $id user id
     *
     * @return void
     */
    public function changeStatus(int $id): void
    {
        $qry = 'UPDATE `' . $this->db->prefixTable('users') . '`
                SET `status` = NOT `status`
                WHERE `id` = ' . $id;

        $this->db->query($qry);
    }

    /**
     * User existence check
     *
     * @param int $id user id
     *
     * @return bool
     */
    public function exists(int $id): bool
    {
        $qry = 'SELECT `id`
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE `id` = ' . $id;

        return (bool)$this->db->query($qry)->getRowArray();
    }

    /**
     * Get user info
     *
     * @param int $id user id
     *
     * @return mixed[]|null
     */
    public function get(int $id): ?array
    {
        $qry = 'SELECT *
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE `id` = ' . $id;

        $rdata = $this->db->query($qry)->getRowArray();
        $data  = $rdata ? (new UserSchema())->filter($rdata) : $rdata;

        return $data;
    }

    /**
     * Email address existence check
     *
     * @param string $email email address
     *
     * @return bool
     */
    public function isEmailExists(string $email): bool
    {
        $qry = 'SELECT `id`
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE `email` = ' . $this->db->escape($email);

        return (bool)$this->db->query($qry)->getRowArray();
    }

    /**
     * Username existence check
     *
     * @param string $username username
     *
     * @return bool
     */
    public function isUsernameExists(string $username): bool
    {
        $qry = 'SELECT `id`
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE `username` = ' . $this->db->escape($username);

        return (bool)$this->db->query($qry)->getRowArray();
    }

    /**
     * Update user info
     *
     * @param int     $id    user id
     * @param mixed[] $rdata user information
     *
     * @return void
     */
    public function update(int $id, array $rdata): void
    {
        $data     = (new UserSchema())->filter($rdata);
        $password = '';

        if ($data['password'] !== '') {
            $password = ', `password` = ' . $this->db->escape(
                password_hash($data['password'], PASSWORD_DEFAULT)
            );
        }

        $qry = 'UPDATE `' . $this->db->prefixTable('users') . '`
                SET `username` = ' . $this->db->escape($data['username'])
                    . ', `email` = ' . $this->db->escape($data['email'])
                    . $password
                    . ', `status` = ' . $data['status']
                    . ', `admin` = ' . $data['admin']
                . ' WHERE `id` = ' . $id;

        $this->db->query($qry);
    }
}

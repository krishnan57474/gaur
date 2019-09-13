<?php

declare(strict_types=1);

namespace App\Models\Admin\Users;

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
                SET `status`= 1,
                    `activation`= 1
                WHERE `id` = ' . $id;

        $this->db->query($qry);
    }

    /**
     * Add user
     *
     * @param array $data user informations
     *
     * @return void
     */
    public function add(array $data): void
    {
        $qry = 'INSERT INTO `' . $this->db->prefixTable('users') . '`(`username`, `email`, `password`, `status`, `activation`, `admin`, `date_added`)
                VALUES ('
                    . $this->db->escape($data['username']) . ', '
                    . $this->db->escape($data['email']) . ', '
                    . $this->db->escape(password_hash($data['password'], PASSWORD_DEFAULT)) . ', '
                    . '1, '
                    . '1, '
                    . $data['admin'] . ', '
                    . $this->db->escape(date('Y-m-d H:i:s'))
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
                SET `status`= NOT `status`
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
     * @return array|null
     */
    public function get(int $id): ?array
    {
        $qry = 'SELECT *
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE `id` = ' . $id;

        return $this->db->query($qry)->getRowArray();
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
     * @param int   $id   user id
     * @param array $data user informations
     *
     * @return void
     */
    public function update(int $id, array $data): void
    {
        $qry = 'UPDATE `' . $this->db->prefixTable('users') . '`
                SET `username` = ' . $this->db->escape($data['username'])
                    . ', `email` = ' . $this->db->escape($data['email'])
                    . (($data['password'] !== '') ? ', `password`= ' . $this->db->escape(password_hash($data['password'], PASSWORD_DEFAULT)) : '')
                    . ', `status`= ' . $data['status']
                    . ', `admin`= ' . $data['admin']
                . ' WHERE `id` = ' . $id;

        $this->db->query($qry);
    }
}

<?php

declare(strict_types=1);

namespace App\Models\Users;

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
     * @param array $data user information
     *
     * @return int
     */
    public function add(array $data): int
    {
        $qry = 'INSERT INTO `' . $this->db->prefixTable('users') . '`(`username`, `email`, `password`, `status`, `activation`, `admin`, `date_added`)
                VALUES ('
                    . $this->db->escape($data['username'])
                    . ', ' . $this->db->escape($data['email'])
                    . ', ' . $this->db->escape(password_hash($data['password'], PASSWORD_DEFAULT))
                    . ', 0'
                    . ', 0'
                    . ', 0'
                    . ', ' . $this->db->escape(date('Y-m-d H:i:s'))
                . ')';

        $this->db->query($qry);
        return $this->db->insertID();
    }

    /**
     * Get user info by id
     *
     * @param int $id user id
     *
     * @return array|null
     */
    public function get(int $id): ?array
    {
        $qry = 'SELECT `username`, `email`, `date_added`
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE `id` = ' . $id;

        return $this->db->query($qry)->getRowArray();
    }

    /**
     * Get user info by email
     *
     * @param string $email email address
     *
     * @return array|null
     */
    public function getByEmail(string $email): ?array
    {
        $qry = 'SELECT `id`, `username`, `email`, `password`, `status`, `activation`, `admin`
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE `email` = ' . $this->db->escape($email);

        return $this->db->query($qry)->getRowArray();
    }

    /**
     * Get user info by username
     *
     * @param string $username username
     *
     * @return array|null
     */
    public function getByUsername(string $username): ?array
    {
        $qry = 'SELECT `id`, `username`, `email`, `password`, `status`, `activation`, `admin`
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE `username` = ' . $this->db->escape($username);

        return $this->db->query($qry)->getRowArray();
    }

    /**
     * Get user password
     *
     * @param int $id user id
     *
     * @return string
     */
    public function getPassword(int $id): string
    {
        $qry = 'SELECT `password`
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE `id` = ' . $id;

        return $this->db->query($qry)->getRowArray()['password'] ?? '';
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
     * Update last visited
     *
     * @param int $id user id
     *
     * @return void
     */
    public function updateLastVisit(int $id): void
    {
        $qry = 'UPDATE `' . $this->db->prefixTable('users') . '`
                SET `last_visited`= ' . $this->db->escape(date('Y-m-d H:i:s'))
                . ' WHERE `id` = ' . $id;

        $this->db->query($qry);
    }

    /**
     * Update account password
     *
     * @param int    $id       user id
     * @param string $password password
     *
     * @return void
     */
    public function updatePassword(int $id, string $password): void
    {
        $qry = 'UPDATE `' . $this->db->prefixTable('users') . '`
                SET `password`= ' . $this->db->escape(password_hash($password, PASSWORD_DEFAULT))
                . ' WHERE `id` = ' . $id;

        $this->db->query($qry);
    }
}

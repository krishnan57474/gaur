<?php

declare(strict_types=1);

namespace App\Models\Users;

use App\Data\Users\UserResetSchema;
use Gaur\Model;

class UserReset extends Model
{
    /**
     * Add user reset
     *
     * @param mixed[] $rdata reset information
     *
     * @return void
     */
    public function add(array $rdata): void
    {
        $data = (new UserResetSchema())->filter($rdata);

        $qry = 'INSERT INTO `' . $this->db->prefixTable('user_resets') . '`(`uid`, `token`, `type`, `expire`)
                VALUES ('
                    . $data['uid']
                    . ', ' . $this->db->escape($data['token'])
                    . ', ' . $data['type']
                    . ', ' . $this->db->escape($data['expire'])
                . ')';

        $this->db->query($qry);
    }

    /**
     * Get user reset
     *
     * @param string $token token
     * @param int    $type  token type
     *
     * @return mixed[]|null
     */
    public function get(string $token, int $type): ?array
    {
        $qry = 'SELECT *
                FROM `' . $this->db->prefixTable('user_resets') . '`
                WHERE `token` = ' . $this->db->escape($token)
                    . ' AND `type` = ' . $type;

        $rdata = $this->db->query($qry)->getRowArray();
        $data  = $rdata ? (new UserResetSchema())->filter($rdata) : $rdata;

        return $data;
    }

    /**
     * Get user reset id
     *
     * @param int $uid  user id
     * @param int $type token type
     *
     * @return int
     */
    public function getID(int $uid, int $type): int
    {
        $qry = 'SELECT `id`
                FROM `' . $this->db->prefixTable('user_resets') . '`
                WHERE `uid` = ' . $uid
                    . ' AND `type` = ' . $type;

        return (int)($this->db->query($qry)->getRowArray()['id'] ?? 0);
    }

    /**
     * Remove user reset
     *
     * @param int $id reset id
     *
     * @return void
     */
    public function remove(int $id): void
    {
        $qry = 'DELETE FROM `' . $this->db->prefixTable('user_resets') . '`
                WHERE `id` = ' . $id;

        $this->db->query($qry);
    }

    /**
     * Update user reset
     *
     * @param int    $id    reset id
     * @param string $token token
     *
     * @return void
     */
    public function update(int $id, string $token): void
    {
        $qry = 'UPDATE `' . $this->db->prefixTable('user_resets') . '`
                SET `token` = ' . $this->db->escape($token)
                . ' WHERE `id` = ' . $id;

        $this->db->query($qry);
    }
}

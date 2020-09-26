<?php

declare(strict_types=1);

namespace App\Models\Admin\Users;

use Gaur\Model;

class UserReset extends Model
{
    /**
     * Get id
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
}

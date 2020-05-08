<?php

declare(strict_types=1);

namespace App\Models\Users;

use Gaur\Model;

class UserReset extends Model
{
    /**
     * Add user reset
     *
     * @param mixed[] $data reset information
     *
     * @return void
     */
    public function add(array $data): void
    {
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
     *
     * @return mixed[]|null
     */
    public function get(string $token): ?array
    {
        $qry = 'SELECT *
                FROM `' . $this->db->prefixTable('user_resets') . '`
                WHERE `token` = ' . $this->db->escape($token);

        return $this->db->query($qry)->getRowArray();
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

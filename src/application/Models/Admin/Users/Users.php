<?php

declare(strict_types=1);

namespace App\Models\Admin\Users;

use Gaur\Model;

class Users extends Model
{
    /**
     * Get users list
     *
     * @param array<string, string[]>|null $filter filter
     * @param array<string, string[]>|null $search search
     * @param int                          $limit  item limit
     * @param int                          $offset page offset
     * @param string[]|null                $order  order by
     *
     * @return mixed[][]
     */
    public function filter(
        ?array $filter,
        ?array $search,
        int $limit,
        int $offset,
        ?array $order
    ): array
    {
        $qry = 'SELECT
                    `id`, `username`, `email`, `status`, `last_visited`
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE 1';

        if (isset($filter['by']) && isset($filter['val'])) {
            foreach ($filter['by'] as $k => $v) {
                $qry .= ' AND `' . $v . '` = ' . $this->db->escape($filter['val'][$k]);
            }
        }

        if (isset($search['by']) && isset($search['val'])) {
            foreach ($search['by'] as $k => $v) {
                $qry .= ' AND `' . $v . '` LIKE \'%' . $this->db->escapeLikeString($search['val'][$k]) . '%\'';
            }

            $qry .= ' ESCAPE \'!\'';
        }

        if ($order) {
            $qry .= ' ORDER BY `' . $order['order'] . '` ' . $order['sort'];
        }

        $qry .= ' LIMIT ' . ($offset ? ($offset . ', ') : '') . $limit;

        return $this->db->query($qry)->getResultArray();
    }

    /**
     * Get total users count
     *
     * @param array<string, string[]>|null $filter filter
     * @param array<string, string[]>|null $search search
     *
     * @return int
     */
    public function filterTotal(
        ?array $filter,
        ?array $search
    ): int
    {
        $qry = 'SELECT COUNT(*) AS `total`
                FROM `' . $this->db->prefixTable('users') . '`
                WHERE 1';

        if (isset($filter['by']) && isset($filter['val'])) {
            foreach ($filter['by'] as $k => $v) {
                $qry .= ' AND `' . $v . '` = ' . $this->db->escape($filter['val'][$k]);
            }
        }

        if (isset($search['by']) && isset($search['val'])) {
            foreach ($search['by'] as $k => $v) {
                $qry .= ' AND `' . $v . '` LIKE \'%' . $this->db->escapeLikeString($search['val'][$k]) . '%\'';
            }

            $qry .= ' ESCAPE \'!\'';
        }

        return $this->db->query($qry)->getRowArray()['total'] ?? 0;
    }
}

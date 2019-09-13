<?php

declare(strict_types=1);

namespace App\Models\Admin\Users;

use Gaur\Model;

class Users extends Model
{
    /**
     * Get users list
     *
     * @param array|null $filter filter
     * @param array|null $search search
     * @param int        $limit  item limit
     * @param int        $offset page offset
     * @param array|null $order  order by
     *
     * @return  array
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

        foreach ($filter['by'] ?? [] as $k => $v) {
            $qry .= ' AND `' . $v . '` = ' . $this->db->escape($filter['val'][$k]);
        }

        foreach ($search['by'] ?? [] as $k => $v) {
            $qry .= ' AND `' . $v . '` REGEXP ' . $this->db->escape(preg_quote($search['val'][$k]));
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
     * @param array|null $filter filter
     * @param array|null $search search
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

        foreach ($filter['by'] ?? [] as $k => $v) {
            $qry .= ' AND `' . $v . '` = ' . $this->db->escape($filter['val'][$k]);
        }

        foreach ($search['by'] ?? [] as $k => $v) {
            $qry .= ' AND `' . $v . '` REGEXP ' . $this->db->escape(preg_quote($search['val'][$k]));
        }

        return $this->db->query($qry)->getRowArray()['total'] ?? 0;
    }
}

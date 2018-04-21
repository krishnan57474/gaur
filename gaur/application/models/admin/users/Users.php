<?php
/**
 * Gaur
 *
 * An open source web application
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017 - 2018, Krishnan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    Gaur
 * @author     Krishnan <krishnan57474@gmail.com>
 * @copyright  Copyright (c) 2017 - 2018, Krishnan
 * @license    https://opensource.org/licenses/MIT   MIT License
 * @link       https://github.com/krishnan57474
 * @since      Version 1.0.0
 */

defined('BASEPATH') OR exit;

/**
 * Users
 *
 * @package     Gaur
 * @subpackage  Model
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Users extends CI_Model
{
    /**
     * Get users list
     *
     * @param   array   filter
     * @param   int     item limit
     * @param   int     page offset
     * @param   array   order by
     *
     * @return  array
     */
    public function get($filter, $limit, $offset, $order)
    {
        $qry = 'SELECT
                    `id`, `username`, `email`, `status`, `activation`, `last_visited`
                FROM `' . $this->db->dbprefix('users') . '`';

        if ($filter['filter'] || $filter['search'])
        {
            $qry .= ' WHERE';
        }

        if ($filter['filter'])
        {
            $qry .= ' `' . $filter['filter']['by'] . '` = ' . $this->db->escape($filter['filter']['val']);
        }

        if ($filter['search'])
        {
            $qry .= $filter['filter'] ? ' AND' : '';
            $qry .= ' `' . $filter['search']['by'] . '` REGEXP ' . $this->db->escape(preg_quote($filter['search']['val']));
        }

        if ($order)
        {
            $qry .= ' ORDER BY `' . $order['order'] . '` ' . $order['sort'];
        }

        $qry .= ' LIMIT ' . ($offset ? ($offset . ', ') : '') . $limit;

        return $this->db->query($qry)->result_array();
    }

    /**
     * Get total users count
     *
     * @param   array  filter
     *
     * @return  int
     */
    public function total($filter)
    {
        $qry = 'SELECT COUNT(*) AS `total`
                FROM `' . $this->db->dbprefix('users') . '`';

        if ($filter['filter'] || $filter['search'])
        {
            $qry .= ' WHERE';
        }

        if ($filter['filter'])
        {
            $qry .= ' `' . $filter['filter']['by'] . '` = ' . $this->db->escape($filter['filter']['val']);
        }

        if ($filter['search'])
        {
            $qry .= $filter['filter'] ? ' AND' : '';
            $qry .= ' `' . $filter['search']['by'] . '` REGEXP ' . $this->db->escape(preg_quote($filter['search']['val']));
        }

        return $this->db->query($qry)->row('total');
    }

    /**
     * Get total users count by group
     *
     * @return  array
     */
    public function total_by_group()
    {
        $qry = 'SELECT `activation`, COUNT(*) AS `total`
                FROM `' . $this->db->dbprefix('users') . '`
                GROUP BY `activation`';

        return $this->db->query($qry)->result_array();
    }

    /**
     * Get recent user
     *
     * @return  string
     */
    public function recent_user()
    {
        $qry = 'SELECT `username`
                FROM `' . $this->db->dbprefix('users') . '`
                ORDER BY `id` DESC
                LIMIT 1';

        return $this->db->query($qry)->row('username');
    }

    /**
     * Username existance check
     *
     * @param   string  username
     *
     * @return  bool
     */
    public function is_username_exists($username)
    {
        $qry = 'SELECT `id`
                FROM `' . $this->db->dbprefix('users') . '`
                WHERE `username` = ' . $this->db->escape($username);

        return ($this->db->query($qry)->num_rows() ? TRUE : FALSE);
    }

    /**
     * Email address existance check
     *
     * @param   string  email address
     *
     * @return  bool
     */
    public function is_email_exists($email)
    {
        $qry = 'SELECT `id`
                FROM `' . $this->db->dbprefix('users') . '`
                WHERE `email` = ' . $this->db->escape($email);

        return ($this->db->query($qry)->num_rows() ? TRUE : FALSE);
    }
}
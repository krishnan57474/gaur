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
 * User
 *
 * @package     Gaur
 * @subpackage  Model
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class User extends CI_Model
{
    /**
     * Admin existance check
     *
     * @param   int     user id
     *
     * @return  bool
     */
    public function is_admin($id)
    {
        $qry = 'SELECT `id`
                FROM `' . $this->db->dbprefix('users') . '`
                WHERE `admin` = 1
                    AND `id` = ' . $id;

        return ($this->db->query($qry)->num_rows() ? TRUE : FALSE);
    }

    /**
     * Update user status
     *
     * @param   int     user id
     *
     * @return  void
     */
    public function change_status($id)
    {
        $qry = 'UPDATE `' . $this->db->dbprefix('users') . '`
                SET `status`= NOT `status`
                WHERE `id` = ' . $id;

        $this->db->query($qry);
    }

    /**
     * Add user
     *
     * @param   array   user informations
     *
     * @return  int
     */
    public function add($data)
    {
        $qry = 'INSERT INTO `' . $this->db->dbprefix('users') . '`(`username`, `email`, `password`, `date_added`)
                    VALUES ('
                        . $this->db->escape($data['username']) . ', '
                        . $this->db->escape($data['email']) . ', '
                        . $this->db->escape(password_hash($data['password'], PASSWORD_DEFAULT)) . ', '
                        . $this->db->escape(date('Y-m-d H:i:s'))
                    . ')';

        $this->db->query($qry);
        return $this->db->insert_id();
    }

    /**
     * Get user info
     *
     * @param   int     user id
     *
     * @return  array
     */
    public function get($id)
    {
        $qry = 'SELECT *
                FROM `' . $this->db->dbprefix('users') . '`
                WHERE `id` = ' . $id;

        return $this->db->query($qry)->row_array();
    }

    /**
     * Update user info
     *
     * @param   int     user id
     * @param   array   user info
     *
     * @return  void
     */
    public function update($id, $data)
    {
        $qry = 'UPDATE `' . $this->db->dbprefix('users') . '`
                SET `username` = ' . $this->db->escape($data['username'])
                    . ', `email` = ' . $this->db->escape($data['email']);

        if ($data['password'] !== '')
        {
            $qry .= ', `password`= ' . $this->db->escape(password_hash($data['password'], PASSWORD_DEFAULT));
        }

        $qry .= ', `status`= ' . $data['status'];
        $qry .= ' WHERE `id` = ' . $id;

        $this->db->query($qry);
    }

    /**
     * User existance check
     *
     * @param   int     user id
     *
     * @return  bool
     */
    public function exists($id)
    {
        $qry = 'SELECT `id`
                FROM `' . $this->db->dbprefix('users') . '`
                WHERE `id` = ' . $this->db->escape($id);

        return ($this->db->query($qry)->num_rows() ? TRUE : FALSE);
    }
}
<?php
/**
 * Gaur
 *
 * An open source web application
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017, Krishnan
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
 * @copyright  Copyright (c) 2017, Krishnan
 * @license    http://opensource.org/licenses/MIT   MIT License
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
     * Account login
     *
     * @param   string  username
     * @param   string  password
     *
     * @return  int
     */
    public function login($username, $password)
    {
        $qry = 'SELECT `id`, `password`
                FROM `' . $this->db->dbprefix('users') . '`
                WHERE `username` = ' . $this->db->escape($username)
                . ' AND `status` = 1
                    AND `activation` = 1';

        $user = $this->db->query($qry)->row_array();

        if (!$user || !password_verify($password, $user['password']))
        {
            return 0;
        }

        return $user['id'];
    }

    /**
     * Activate user account
     *
     * @param   int     user id
     *
     * @return  void
     */
    public function activate($id)
    {
        $qry = 'UPDATE `' . $this->db->dbprefix('users') . '`
                SET `status`= 1, `activation`= 1
                WHERE `id` = ' . $id;

        $this->db->query($qry);
    }

    /**
     * Get user info by id
     *
     * @param   int     user id
     *
     * @return  array
     */
    public function get($id)
    {
        $qry = 'SELECT `username`, `email`, `date_added`
                FROM `' . $this->db->dbprefix('users') . '`
                WHERE `id` = ' . $id;

        return $this->db->query($qry)->row_array();
    }

    /**
     * Update last login
     *
     * @param   int     user id
     *
     * @return  void
     */
    public function update_last_visit($id)
    {
        $qry = 'UPDATE `' . $this->db->dbprefix('users') . '`
                SET `last_visited`= ' . $this->db->escape(date('Y-m-d H:i:s'))
                . ' WHERE `id` = ' . $id;

        $this->db->query($qry);
    }

    /**
     * Get user password
     *
     * @param   int     user id
     *
     * @return  string
     */
    public function get_password($id)
    {
        $qry = 'SELECT `password`
                FROM `' . $this->db->dbprefix('users') . '`
                WHERE `id` = ' . $id;

        return $this->db->query($qry)->row('password');
    }

    /**
     * Change account password
     *
     * @param   int     user id
     * @param   string  password
     *
     * @return  void
     */
    public function change_password($id, $password)
    {
        $qry = 'UPDATE `' . $this->db->dbprefix('users') . '`
                SET `password`= ' . $this->db->escape(password_hash($password, PASSWORD_DEFAULT))
                . ' WHERE `id` = ' . $id;

        $this->db->query($qry);
    }
}
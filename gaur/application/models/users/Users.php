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
 * Users
 *
 * @package     Gaur
 * @subpackage  Model
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Users extends CI_Model
{
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
                FROM `gaur_users`
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
                FROM `gaur_users`
                WHERE `email` = ' . $this->db->escape($email);

        return ($this->db->query($qry)->num_rows() ? TRUE : FALSE);
    }

    /**
     * Check user status
     *
     * @param   string  email
     *
     * @return  bool
     */
    public function is_active($email)
    {
        $qry = 'SELECT `id`
                FROM `gaur_users`
                WHERE `status` = 1
                    AND `activation` = 1
                    AND `email` = ' . $this->db->escape($email);

        return ($this->db->query($qry)->num_rows() ? TRUE : FALSE);
    }

    /**
     * Get user info
     *
     * @param   string  email address
     *
     * @return  array
     */
    public function get_user($email)
    {
        $qry = 'SELECT `id`, `username`
                FROM `gaur_users`
                WHERE `email` = ' . $this->db->escape($email);

        return $this->db->query($qry)->row_array();
    }
}
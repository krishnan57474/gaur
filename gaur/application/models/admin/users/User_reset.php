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
 * User reset
 *
 * @package     Gaur
 * @subpackage  Model
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class User_reset extends CI_Model
{
    /**
     * Add user reset
     *
     * @param   array  reset informations
     *
     * @return  void
     */
    public function add($data)
    {
        $qry = 'INSERT INTO `gaur_user_resets`(`uid`, `token`, `type`, `expire`)
                    VALUES ('
                        . $data['uid'] . ', '
                        . $this->db->escape($data['token']) . ', '
                        . $data['type'] . ', '
                        . $this->db->escape($data['expire'])
                    . ')';

        $this->db->query($qry);
    }

    /**
     * Get user reset
     *
     * @param   int     user id
     * @param   int     reset type
     *
     * @return  array
     */
    public function get($uid, $type)
    {
        $qry = 'SELECT *
                FROM `gaur_user_resets`
                WHERE `uid` = ' . $uid
                . ' AND `type` = ' . $type;

        return $this->db->query($qry)->row_array();
    }
}
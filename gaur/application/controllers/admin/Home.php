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
 * Admin home page
 *
 * @package     Gaur
 * @subpackage  Controller
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Home extends CI_Controller
{
    /**
     * Default page for this controller
     *
     * @return  void
     */
    public function _remap()
    {
        // prevent non logged users
        if (!isset($_SESSION['user_id']))
        {
            // login redirect
            $_SESSION['redirect'] = 'admin';
            $this->session->mark_as_flash('redirect');
            session_write_close();

            $this->load->helper('url');
            redirect('account/login', 'location');
        }

        if (!isset($_SESSION['is_admin']))
        {
            $this->load->model('admin/users/user', NULL, TRUE);

            $_SESSION['is_admin'] = $this->user->is_admin($_SESSION['user_id']);
        }

        // prevent non admin users
        if (!$_SESSION['is_admin'])
        {
            session_write_close();
            show_404(NULL, FALSE);
        }

        if ($this->input->method() === 'post'
            && $this->input->is_ajax_request()
            && $this->load->helper('form_input')
            && form_input('j-af') === 'r')
        {
            return $this->_aaction();
        }

        $this->load->view('app/default/admin/home');
    }

    /**
     * Get total users count
     *
     * @param   array   form data
     *
     * @return  void
     */
    private function _aaction_getuserstotal(&$fdata)
    {
        $this->load->model('admin/users/users', NULL, TRUE);

        $total_users = $this->users->total_by_group();

        foreach (array_splice($total_users, 0) as $v)
        {
            $total_users[$v['activation']] = $v['total'];
        }

        $fdata['data'] = $total_users;
    }

    /**
     * Get recent user info
     *
     * @param   array   form data
     *
     * @return  void
     */
    private function _aaction_getrecentuser(&$fdata)
    {
        $this->load->model('admin/users/users', NULL, TRUE);
        $this->load->helper('html');

        $recent_user = $this->users->recent_user();

        $fdata['data'] = hentities($recent_user);
    }

    /**
     * Ajax form action
     *
     * @return  void
     */
    private function _aaction()
    {
        $fdata = array();
        $fdata['status'] = 0;

        if (!preg_match('#[^a-z]#', form_input('action'))
            && method_exists($this, '_aaction_' . form_input('action')))
        {
            $fdata['status'] = 1;
            $this->{'_aaction_' . form_input('action')}($fdata);
        }

        $this->output->set_content_type('json')->set_output(json_encode($fdata));
    }
}
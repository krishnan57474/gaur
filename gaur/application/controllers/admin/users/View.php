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
 * View a user info
 *
 * @package     Gaur
 * @subpackage  Controller
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class View extends CI_Controller
{
    /**
     * Default page for this controller
     *
     * @param   int   user id
     *
     * @return  void
     */
    public function _remap($id)
    {
        // prevent non logged users
        if (!isset($_SESSION['user_id']))
        {
            // login redirect
            $_SESSION['redirect'] = 'admin/users';
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

        // prevent non admin users, invalid id
        if (!$_SESSION['is_admin']
            || $id < 1)
        {
            session_write_close();
            show_404(NULL, FALSE);
        }

        $id = (int)$id;

        if ($this->input->method() === 'post'
            && $this->input->is_ajax_request()
            && $this->load->helper('form_input')
            && form_input('j-af') === 's')
        {
            return $this->_aaction($id);
        }

        $this->load->model('admin/users/user', NULL, TRUE);

        $user = $this->user->get($id);

        if (!$user)
        {
            show_404(NULL, FALSE);
        }

        $this->load->helper(array('html', 'date'));

        $data = array();
        $data['user'] = $user;

        $this->load->view('app/default/admin/users/view', $data);
    }

    /**
     * Ajax form submit
     *
     * @param   array   form data
     * @param   int     user id
     *
     * @return  void
     */
    private function _aaction_submit(&$fdata, $id)
    {
        $this->load->model('admin/users/user', NULL, TRUE);

        $user = $this->user->get($id);

        if (!$user || $user['activation'])
        {
            $fdata['status'] = 0;
            return;
        }

        $this->load->model('admin/users/user_reset');

        $reset = $this->user_reset->get($user['id'], 1);

        if (!$reset)
        {
            $fdata['status'] = 0;
            return;
        }

        $this->load->library('mail');
        $this->load->helper('xhtml');

        $data = array();
        $data['to']         = $user['email'];
        $data['subject']    = 'Activate your account';
        $data['username']   = $user['username'];
        $data['token']      = $reset['token'];

        // send email verification
        $status = $this->mail->send('email/default/account/email/activation', $data);

        if ($status)
        {
            $fdata['data'] = 'Verification email has been sent successfully';
        }
        else
        {
            $fdata['errors'] = array(
                'Oops! something went wrong please try again later'
            );
        }
    }

    /**
     * Ajax form action
     *
     * @param   int   user id
     *
     * @return  void
     */
    private function _aaction($id)
    {
        $fdata = array();
        $fdata['status'] = 1;

        switch ($fdata['status'] ? form_input('j-af') : '')
        {
            case 's':
            {
                $this->_aaction_submit($fdata, $id);
                break;
            }
        }

        $this->output->set_content_type('json')->set_output(json_encode($fdata));
    }
}
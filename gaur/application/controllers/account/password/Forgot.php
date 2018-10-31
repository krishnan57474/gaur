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
 * Forgot password
 *
 * @package     Gaur
 * @subpackage  Controller
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Forgot extends CI_Controller
{
    /**
     * Filtered inputs
     *
     * @var array
     */
    private $finputs;

    /**
     * Input errors
     *
     * @var array
     */
    private $errors;

    /**
     * Default page for this controller
     *
     * @return  void
     */
    public function _remap()
    {
        // prevent logged users
        if (isset($_SESSION['user_id']))
        {
            $this->load->helper('url');
            redirect('', 'location');
        }

        if ($this->input->method() === 'post'
            && $this->input->is_ajax_request()
            && $this->load->helper('form_input')
            && form_input('j-af') === 's')
        {
            return $this->_aaction();
        }

        $data = array();

        $data['csrf'] = array(
            'name' => md5(uniqid(mt_rand(), TRUE)),
            'hash' => md5(uniqid(mt_rand(), TRUE))
        );

        $_SESSION['csrf-apf'] = array($data['csrf']['name'], $data['csrf']['hash']);
        $this->session->mark_as_flash('csrf-apf');
        session_write_close();

        $this->load->view('app/default/account/password/forgot', $data);
    }

    /**
     * Validate user inputs
     *
     * @return  bool
     */
    private function _validate()
    {
        $this->finputs = array();
        $this->errors = array();

        $this->finputs['email'] = form_input('email');

        if ($this->finputs['email'] === '')
        {
            $this->errors[] = 'Please enter your e-mail address!';
            return FALSE;
        }

        if (!filter_var($this->finputs['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->errors[] = 'Email address does not apear to be valid!';
        }
        elseif(mb_strlen($this->finputs['email']) > 254)
        {
            $this->errors[] = 'Email address must be lessthan 255 characters!';
        }
        else
        {
            $this->load->model('users/users', NULL, TRUE);

            if (!$this->users->is_active($this->finputs['email']))
            {
                $this->errors[] = 'Unable to sent confirmation to the provided e-mail address!';
            }
        }

        return !$this->errors;
    }

    /**
     * Ajax form submit
     *
     * @param   array   form data
     *
     * @return  void
     */
    private function _aaction_submit(&$fdata)
    {
        if (!$this->_validate())
        {
            $this->session->mark_as_flash('csrf-apf');
            session_write_close();

            $fdata['errors'] = $this->errors;
            return;
        }

        $this->load->library('mail');
        $this->load->model('users/user_reset');
        $this->load->helper('xhtml');

        // normalise chars
        $this->finputs['email'] = mb_strtolower($this->finputs['email']);

        $user = $this->users->get_user($this->finputs['email']);

        // generate random token
        $token = md5(uniqid(mt_rand(), TRUE));

        // add forgot password
        $this->user_reset->add(array(
            'uid'     => $user['id'],
            'token'   => $token,
            'type'    => 2,
            'expire'  => date('Y-m-d H:i:s', strtotime('1 hour'))
        ));

        $data = array();
        $data['to']         = $this->finputs['email'];
        $data['subject']    = 'Password reset request';
        $data['username']   = $user['username'];
        $data['token']      = $token;

        // send password reset
        $status = $this->mail->send('email/default/account/password/forgot', $data);

        if ($status)
        {
            $fdata['data'] = 'Congratulations! a password reset has been sent to the provided e-mail address.';
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
     * @return  void
     */
    private function _aaction()
    {
        $fdata = array();
        $fdata['status'] = 0;

        if (isset($_SESSION['csrf-apf']))
        {
            $fdata['status'] = (int)(form_input($_SESSION['csrf-apf'][0]) === $_SESSION['csrf-apf'][1]);
        }

        switch ($fdata['status'] ? form_input('j-af') : '')
        {
            case 's':
            {
                $this->_aaction_submit($fdata);
                break;
            }
        }

        $this->output->set_content_type('json')->set_output(json_encode($fdata));
    }
}
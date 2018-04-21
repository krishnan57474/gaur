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
 * Account registeration
 *
 * @package     Gaur
 * @subpackage  Controller
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Register extends CI_Controller
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

        $_SESSION['csrf-ar'] = array($data['csrf']['name'], $data['csrf']['hash']);
        $this->session->mark_as_flash('csrf-ar');
        session_write_close();

        $this->load->view('app/default/account/register', $data);
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

        foreach (array('username', 'email') as $field)
        {
            $this->finputs[$field] = form_input($field);

            if (!$this->finputs[$field])
            {
                $this->errors[] = 'Please fill all required fields';
                return FALSE;
            }
        }

        if (preg_match('#[^a-zA-Z0-9]#', $this->finputs['username'])
            || !preg_match('#[^0-9]#', $this->finputs['username']))
        {
            $this->errors[] = 'Username does not appear to be valid. Allowed characters [a-zA-Z0-9]';
        }
        elseif (strlen($this->finputs['username']) < 4
            || strlen($this->finputs['username']) > 32)
        {
            $this->errors[] = 'Username must be between 4 and 32 characters';
        }
        else
        {
            $this->load->model('users/users', NULL, TRUE);

            if ($this->users->is_username_exists($this->finputs['username']))
            {
                $this->errors[] = 'Username is already in use';
            }
        }

        if (mb_strlen($this->finputs['email']) > 254
            || !filter_var($this->finputs['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->errors[] = 'E-mail address does not appear to be valid';
        }
        else
        {
            if (!isset($this->users))
            {
                $this->load->model('users/users', NULL, TRUE);
            }

            if ($this->users->is_email_exists($this->finputs['email']))
            {
                $this->errors[] = 'E-mail address is already in use';
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
            $this->session->mark_as_flash('csrf-ar');
            session_write_close();

            $fdata['errors'] = $this->errors;
            return;
        }

        $this->load->library('mail');
        $this->load->model('users/user');
        $this->load->model('users/user_reset');
        $this->load->helper('xhtml');

        // normalise chars
        $this->finputs['username'] = strtolower($this->finputs['username']);
        $this->finputs['email'] = mb_strtolower($this->finputs['email']);

        // random password
        $this->finputs['password'] = md5(uniqid(mt_rand(), TRUE));

        // add user
        $uid = $this->user->add($this->finputs);

        // generate random token
        $token = md5(uniqid(mt_rand(), TRUE));

        // add account verification
        $this->user_reset->add(array(
            'uid'     => $uid,
            'token'   => $token,
            'type'    => 1,
            'expire'  => '0000-00-00 00:00:00'
        ));

        // send email verification
        $this->mail->account_activation(array(
            'username' => $this->finputs['username'],
            'email'    => $this->finputs['email'],
            'token'    => $token
        ));

        $fdata['data'] = array(
            'Congratulations! your new account has been successfully created!',
            'A confirmation has been sent to the provided e-mail address. You will need to follow the instructions in that message in order to gain access to the site.'
        );
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

        if (isset($_SESSION['csrf-ar']))
        {
            $fdata['status'] = (int)(form_input($_SESSION['csrf-ar'][0]) === $_SESSION['csrf-ar'][1]);
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
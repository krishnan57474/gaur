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
 * Edit a user
 *
 * @package     Gaur
 * @subpackage  Controller
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Edit extends CI_Controller
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

        $this->load->helper('html');

        $data = array();

        $data['user'] = $user;

        $data['csrf'] = array(
            'name' => md5(uniqid(mt_rand(), TRUE)),
            'hash' => md5(uniqid(mt_rand(), TRUE))
        );

        $_SESSION['csrf-aue'] = array($data['csrf']['name'], $data['csrf']['hash']);
        $this->session->mark_as_flash('csrf-aue');
        session_write_close();

        $this->load->view('app/default/admin/users/edit', $data);
    }

    /**
     * Validate username and email
     *
     * @param   int     user id
     *
     * @return  bool
     */
    private function _validate_user($id)
    {
        $this->load->model('admin/users/users');

        $user = $this->user->get($id);

        if ($this->finputs['username'] !== $user['username']
            && $this->users->is_username_exists($this->finputs['username']))
        {
            $this->errors[] = 'Username is already in use!';
        }

        if ($this->finputs['email'] !== $user['email']
            && $this->users->is_email_exists($this->finputs['email']))
        {
            $this->errors[] = 'E-mail address is already in use!';
        }

        return !$this->errors;
    }

    /**
     * Validate user inputs
     *
     * @return  bool
     */
    private function _validate()
    {
        $this->errors = array();
        $this->finputs = array();

        foreach (array('username', 'email', 'status') as $field)
        {
            $this->finputs[$field] = form_input($field);

            if ($this->finputs[$field] === '')
            {
                $this->errors[] = 'Please fill all required fields!';
                return FALSE;
            }
        }

        foreach (array('password', 'password-confirm') as $field)
        {
            $this->finputs[$field] = form_input($field);
        }

        if (preg_match('#[^a-zA-Z0-9]#', $this->finputs['username'])
            || !preg_match('#[a-zA-Z]#', $this->finputs['username']))
        {
            $this->errors[] = 'Username does not appear to be valid!';
        }
        elseif (strlen($this->finputs['username']) < 3
            || strlen($this->finputs['username']) > 32)
        {
            $this->errors[] = 'Username must be between 3 and 32 characters!';
        }

        if (!filter_var($this->finputs['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->errors[] = 'Email address does not apear to be valid!';
        }
        elseif(mb_strlen($this->finputs['email']) > 254)
        {
            $this->errors[] = 'Email address must be lessthan 255 characters!';
        }

        if ($this->finputs['password'] !== ''
            && (mb_strlen($this->finputs['password']) < 4
                || mb_strlen($this->finputs['password']) > 64))
        {
            $this->errors[] = 'Password must be between 4 and 64 characters!';
        }

        if ($this->finputs['password'] !== $this->finputs['password-confirm'])
        {
            $this->errors[] = 'Password confirmation does not match the password!';
        }

        if (!preg_match('#^[0-1]$#', $this->finputs['status']))
        {
            $this->errors[] = 'Status does not appear to be valid!';
        }

        return !$this->errors;
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

        if (!$this->user->exists($id))
        {
            $fdata['status'] = 0;
            return;
        }

        if (!$this->_validate()
            || !$this->_validate_user($id))
        {
            $this->session->mark_as_flash('csrf-aue');
            session_write_close();

            $fdata['errors'] = $this->errors;
            return;
        }

        // normalise chars
        $this->finputs['username'] = strtolower($this->finputs['username']);
        $this->finputs['email'] = mb_strtolower($this->finputs['email']);
        $this->finputs['status'] = (int)$this->finputs['status'];

        if ((int)$_SESSION['user_id'] === $id)
        {
            $this->finputs['status'] = 1;
        }

        $this->user->update($id, $this->finputs);

        $fdata['data'] = array(
            'Congratulations! user has been successfully updated.',
            'admin/users'
        );
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
        $fdata['status'] = 0;

        if (isset($_SESSION['csrf-aue']))
        {
            $fdata['status'] = (int)(form_input($_SESSION['csrf-aue'][0]) === $_SESSION['csrf-aue'][1]);
        }

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
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
 * Account login
 *
 * @package     Gaur
 * @subpackage  Controller
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Login extends CI_Controller
{
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

        // keep temporary redirect
        if (isset($_SESSION['redirect']))
        {
            $this->session->mark_as_flash('redirect');
        }

        $data = array();

        $data['csrf'] = array(
            'name' => md5(uniqid(mt_rand(), TRUE)),
            'hash' => md5(uniqid(mt_rand(), TRUE))
        );

        $_SESSION['csrf-al'] = array($data['csrf']['name'], $data['csrf']['hash']);
        $this->session->mark_as_flash('csrf-al');
        session_write_close();

        $this->load->view('app/default/account/login', $data);
    }

    /**
     * Validate user inputs
     *
     * @return  bool
     */
    private function _validate()
    {
        $finputs = array();
        $this->errors = array();

        foreach (array('username', 'password') as $field)
        {
            $finputs[$field] = form_input($field);

            if (!$finputs[$field])
            {
                $this->errors[] = 'Please fill all required fields';
                return FALSE;
            }
        }

        if (preg_match('#[^a-zA-Z0-9]#', $finputs['username'])
            || strlen($finputs['username']) < 4
            || strlen($finputs['username']) > 32
            || mb_strlen($finputs['password']) < 4
            || mb_strlen($finputs['password']) > 64)
        {
            $this->errors[] = 'Incorrect username or password';
        }
        else
        {
            $this->load->model('users/user', NULL, TRUE);

            $uid = $this->user->login($finputs['username'], $finputs['password']);

            // store user id
            $_SESSION['user_id'] = $uid;

            if (!$uid)
            {
                $this->errors[] = 'Incorrect username or password';
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
            // keep temporary redirect
            if (isset($_SESSION['redirect']))
            {
                $this->session->mark_as_flash('redirect');
            }

            $this->session->mark_as_flash('csrf-al');
            session_write_close();

            $fdata['errors'] = $this->errors;
            return;
        }

        $this->user->update_last_visit($_SESSION['user_id']);

        // default login success redirect
        $url = 'account';

        if (isset($_SESSION['redirect']))
        {
            $url = $_SESSION['redirect'];
        }

        $fdata['data'] = array(
            'You have successfully logged in',
            $url
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

        if (isset($_SESSION['csrf-al']))
        {
            $fdata['status'] = (int)(form_input($_SESSION['csrf-al'][0]) === $_SESSION['csrf-al'][1]);
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
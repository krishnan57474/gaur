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
 * Update password
 *
 * @package     Gaur
 * @subpackage  Controller
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Home extends CI_Controller
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
        // prevent non logged users
        if (!isset($_SESSION['user_id']))
        {
            // login redirect
            $_SESSION['redirect'] = 'account/password';
            $this->session->mark_as_flash('redirect');
            session_write_close();

            $this->load->helper('url');
            redirect('account/login', 'location');
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

        $_SESSION['csrf-aph'] = array($data['csrf']['name'], $data['csrf']['hash']);
        $this->session->mark_as_flash('csrf-aph');
        session_write_close();

        $this->load->view('app/default/account/password/home', $data);
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

        foreach (array('cpassword', 'password', 'password-confirm') as $field)
        {
            $this->finputs[$field] = form_input($field);

            if (!$this->finputs[$field])
            {
                $this->errors[] = 'Please fill all required fields';
                return FALSE;
            }
        }

        if (mb_strlen($this->finputs['password']) < 4
            || mb_strlen($this->finputs['password']) > 64)
        {
            $this->errors[] = 'Password must be between 4 and 64 characters';
        }

        if ($this->finputs['password'] !== $this->finputs['password-confirm'])
        {
            $this->errors[] = 'Password confirmation does not match the password';
        }

        if ($this->errors)
        {
            return FALSE;
        }

        if (mb_strlen($this->finputs['cpassword']) < 4
            || mb_strlen($this->finputs['cpassword']) > 64)
        {
            $this->errors[] = 'Incorrect current password';
        }
        else
        {
            $this->load->model('users/user', NULL, TRUE);

            $password = $this->user->get_password($_SESSION['user_id']);

            if (!password_verify($this->finputs['cpassword'], $password))
            {
                $this->errors[] = 'Incorrect current password';
            }
            elseif ($this->finputs['cpassword'] === $this->finputs['password'])
            {
                $this->errors[] = 'Please use different password';
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
            $this->session->mark_as_flash('csrf-aph');
            session_write_close();

            $fdata['errors'] = $this->errors;
            return;
        }

        $this->user->change_password($_SESSION['user_id'], $this->finputs['password']);
        $fdata['data'] = 'Congratulations! your password has been successfully updated.';
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

        if (isset($_SESSION['csrf-aph']))
        {
            $fdata['status'] = (int)(form_input($_SESSION['csrf-aph'][0]) === $_SESSION['csrf-aph'][1]);
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
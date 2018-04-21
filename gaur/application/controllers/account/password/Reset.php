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
 * Password reset
 *
 * @package     Gaur
 * @subpackage  Controller
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Reset extends CI_Controller
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
     * @param   string  token
     *
     * @return  void
     */
    public function _remap($token)
    {
        // prevent logged users
        if (isset($_SESSION['user_id']) || $token === 'index')
        {
            $this->load->helper('url');
            redirect('', 'location');
        }

        if ($this->input->method() === 'post'
            && $this->input->is_ajax_request()
            && $this->load->helper('form_input')
            && preg_match('#^[vs]$#', form_input('j-af')))
        {
            return $this->_aaction($token);
        }

        $data = array();
        $data['verify_reset'] = TRUE;

        if (isset($_SESSION['password_reset'])
            && $_SESSION['password_reset']['token'] === $token)
        {
            $data['verify_reset'] = FALSE;

            $data['csrf'] = array(
                'name' => md5(uniqid(mt_rand(), TRUE)),
                'hash' => md5(uniqid(mt_rand(), TRUE))
            );

            $_SESSION['csrf-apr'] = array($data['csrf']['name'], $data['csrf']['hash']);
            $this->session->mark_as_flash(array('csrf-apr', 'password_reset'));
            session_write_close();
        }

        $this->load->view('app/default/account/password/reset', $data);
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

        foreach (array('password', 'password-confirm') as $field)
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

        return !$this->errors;
    }

    /**
     * Validate password reset token
     *
     * @param   array   form data
     * @param   string  token
     *
     * @return  void
     */
    private function _aaction_validate(&$fdata, $token)
    {
        $this->load->model('users/user_reset', NULL, TRUE);

        $reset = $this->user_reset->get($token, 2);

        if (!$reset || strtotime($reset['expire']) < strtotime('now'))
        {
            if ($reset)
            {
                $this->user_reset->remove($reset['id']);
            }

            $fdata['errors'] = array(
                'Opps! verification failed. Invalid verification code or expired verification code.'
            );

            return;
        }

        $_SESSION['password_reset'] = $reset;

        $this->session->mark_as_flash('password_reset');
        session_write_close();

        $fdata['data'] = 'Congratulations! Your password reset request has been successfully verified!. Please refresh the page if you are not redirected within a few seconds.';
    }

    /**
     * Ajax form submit
     *
     * @param   array   form data
     * @param   string  token
     *
     * @return  void
     */
    private function _aaction_submit(&$fdata, $token)
    {
        $reset = NULL;

        if (isset($_SESSION['password_reset']))
        {
            $reset = $_SESSION['password_reset'];
        }

        if (!$reset || $reset['token'] !== $token)
        {
            $fdata['status'] = 0;
            return;
        }

        if (strtotime($reset['expire']) < strtotime('now'))
        {
            $this->load->model('users/user_reset', NULL, TRUE);

            $this->user_reset->remove($reset['id']);
            unset($_SESSION['password_reset']);
            session_write_close();

            $fdata['status'] = 0;
            return;
        }

        if (isset($_SESSION['csrf-apr']))
        {
            $fdata['status'] = (int)(form_input($_SESSION['csrf-apr'][0]) === $_SESSION['csrf-apr'][1]);
        }

        if (!$fdata['status'])
        {
            return;
        }

        if (!$this->_validate())
        {
            $this->session->mark_as_flash(array('csrf-apr', 'password_reset'));
            session_write_close();

            $fdata['errors'] = $this->errors;
            return;
        }

        $this->load->model('users/user_reset', NULL, TRUE);
        $this->load->model('users/user');

        $this->user_reset->remove($reset['id']);
        $this->user->change_password($reset['uid'], $this->finputs['password']);

        unset($_SESSION['password_reset']);
        session_write_close();

        $fdata['data'] = 'Congratulations! your password has been successfully changed. Now you can log in by using your new password.';
    }

    /**
     * Ajax form action
     *
     * @param   string  token
     *
     * @return  void
     */
    private function _aaction($token)
    {
        $fdata = array();
        $fdata['status'] = 0;

        if (!preg_match('#[^a-zA-Z0-9]#', $token)
            && strlen($token) === 32)
        {
            $fdata['status'] = 1;
        }

        switch ($fdata['status'] ? form_input('j-af') : '')
        {
            case 'v':
            {
                $this->_aaction_validate($fdata, $token);
                break;
            }

            case 's':
            {
                $this->_aaction_submit($fdata, $token);
                break;
            }
        }

        $this->output->set_content_type('json')->set_output(json_encode($fdata));
    }
}
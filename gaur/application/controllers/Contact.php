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
 * Contact page
 *
 * @package     Gaur
 * @subpackage  Controller
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Contact extends CI_Controller
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

        $_SESSION['csrf-c'] = array($data['csrf']['name'], $data['csrf']['hash']);
        $this->session->mark_as_flash('csrf-c');
        session_write_close();

        $this->load->view('app/default/contact', $data);
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
        $rfields = array(
            'name',
            'email',
            'phone',
            'message'
        );

        foreach ($rfields as $field)
        {
            $this->finputs[$field] = form_input($field);

            if ($this->finputs[$field] === '')
            {
                $this->errors[] = 'Please fill all required fields!';
                return FALSE;
            }
        }

        if (mb_strlen($this->finputs['name']) > 20)
        {
            $this->errors[] = 'Name must be lessthan 21 characters!';
        }

        if (!filter_var($this->finputs['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->errors[] = 'Email address does not apear to be valid!';
        }
        elseif(mb_strlen($this->finputs['email']) > 254)
        {
            $this->errors[] = 'Email address must be lessthan 255 characters!';
        }

        if (preg_match('#[^0-9]#', $this->finputs['phone']))
        {
            $this->errors[] = 'Phone number does not appear to be valid!';
        }
        elseif(strlen($this->finputs['phone']) !== 10)
        {
            $this->errors[] = 'Phone number must be 10 digits!';
        }

        if (mb_strlen($this->finputs['message']) > 1024)
        {
            $this->errors[] = 'Message must be lessthan 1025 characters!';
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
            $this->session->mark_as_flash('csrf-c');
            session_write_close();

            $fdata['errors'] = $this->errors;
            return;
        }

        $this->load->library('mail');
        $this->load->helper('xhtml');

        $data = array();
        $data['to']         = 'contact@example.com';
        $data['subject']    = 'Contact enquiry';
        $data['inputs']     = $this->finputs;

        // send email
        $status = $this->mail->send('email/default/contact', $data);

        if ($status)
        {
            $fdata['data'] = 'Congratulations! your message has been successfully sent. We will send you a reply as soon as possible. Thank you for your interest in ' . config_item('site_name');
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

        if (isset($_SESSION['csrf-c']))
        {
            $fdata['status'] = (int)(form_input($_SESSION['csrf-c'][0]) === $_SESSION['csrf-c'][1]);
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
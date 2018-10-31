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
 * Email sender
 *
 * @package     Gaur
 * @subpackage  Library
 * @author      Krishnan <krishnan57474@gmail.com>
 */
class Mail
{
    /**
     * CI object
     *
     * @var obj
     */
    private $ci;

    /**
     * Load initial values
     *
     * @return  void
     */
    public function __construct()
    {
        $this->ci = &get_instance();

        $this->ci->load->config('email');
        $this->ci->load->library('email');
    }

    /**
     * Send email
     *
     * @param   string  view path
     * @param   array   email data
     *
     * @return  bool
     */
    public function send($path, $data)
    {
        $msg = $this->ci->load->view($path, $data, TRUE);

        $this->ci->email->from('no-reply@example.com', config_item('site_name'));
        $this->ci->email->to($data['to']);
        $this->ci->email->subject($data['subject']);
        $this->ci->email->message($msg);

        return $this->ci->email->send();
    }
}
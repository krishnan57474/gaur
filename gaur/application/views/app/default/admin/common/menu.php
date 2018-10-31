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

?>
    <header class="container border-bottom mb-3 mt-sm-2 pb-sm-2">
        <div class="row">
            <div class="col-sm-3 d-none d-sm-block">
                <div class="text-center">
                    <a href="" class="h1 mb-0"><?php echo config_item('site_name'); ?></a>
                </div>
            </div>

            <div class="col-sm-9">
                <input id="sm-state" type="checkbox">
                <label class="sm-btn" for="sm-state">
                    <span class="sm-icon"></span>
                    <span class="sm-title"><?php echo config_item('site_name'); ?></span>
                </label>
                <ul class="sm">
                    <li>
                        <a href="admin/users"><span class="oi oi-people"></span> Users</a>
                    </li>
                    <li>
                        <a href="account/logout"><span class="oi oi-account-logout"></span> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
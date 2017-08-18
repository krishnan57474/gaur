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

?>
    <div class="container c-ptb-2">
        <div class="row">
            <div class="col-md-3 hidden-sm hidden-xs">
                <p class="c-ma text-center">
                    <a href="" class="h1 c-ma"><?php echo config_item('site_name'); ?></a>
                </p>
            </div>

            <div class="col-md-9">
                <input id="menu-state" type="checkbox">
                <label class="menu-btn" for="menu-state">
                    <span class="menu-btn-icon"></span>
                    <a href="" class="menu-btn-title"><?php echo config_item('site_name'); ?></a>
                </label>
                <ul id="main-menu" class="sm sm-clean">
                    <li>
                        <a href="admin/users">Users</a>
                    </li>
                    <li>
                        <a href="account/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
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

$this->load->view('app/default/common/head_top');

?>
    <title>Account - <?php echo config_item('site_name'); ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?php $this->load->view('app/default/common/css'); ?>

    <style>
        .text-overflow {
            text-overflow: ellipsis;
            overflow: hidden;
        }
    </style>

    <?php
        $this->load->view('app/default/common/head_bottom');
        $this->load->view('app/default/common/menu');
    ?>

    <main class="container">
        <div class="row">
            <div class="col-sm-3">
                <table class="table table-bordered collapse-container">
                    <thead>
                        <tr class="panel panel-default">
                            <th class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-9">Account</div>
                                    <div class="col-xs-3 visible-xs text-right collapse-btn">
                                        <span class="glyphicon glyphicon-menu-hamburger"></span>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="hidden-xs collapse-content">
                        <tr>
                            <td>Account</td>
                        </tr>
                        <tr>
                            <td><a href="account/password">Password</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-9">
                <ol class="breadcrumb">
                    <li>You are here: </li>
                    <li><a href="">Home</a></li>
                    <li class="active">Account</li>
                </ol>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1 class="panel-title">Account details</h1>
                    </div>
                    <div class="panel-body">
                        <div class="clearfix form-group">
                            <div class="col-sm-4">Username</div>
                            <div class="col-sm-8 text-overflow"><?php echo hentities($user['username']); ?></div>
                        </div>
                        <div class="clearfix form-group">
                            <div class="col-sm-4">Email</div>
                            <div class="col-sm-8 text-overflow"><?php echo hentities($user['email']); ?></div>
                        </div>
                        <div class="clearfix">
                            <div class="col-sm-4">Member since</div>
                            <div class="col-sm-8"><?php echo date('F, Y', strtotime($user['date_added'])); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php $this->load->view('app/default/common/foot_top'); ?>

    <script>
    (function ($) {
        "use strict";

        function collapse() {
            $(".collapse-btn").on("click", function () {
                $(this).closest(".collapse-container").find(".collapse-content").toggleClass("hidden-xs");
            });
        }

        function init() {
            $ = jQuery;
            collapse();
        }

        window._jq = [init];
    }());
    </script>

    <?php
        $this->load->view('app/default/common/js');
        $this->load->view('app/default/common/foot_bottom');
    ?>
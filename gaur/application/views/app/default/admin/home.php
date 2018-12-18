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

$this->load->view('app/default/common/head_top');

?>
    <title>Admin - <?php echo config_item('site_name'); ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?php $this->load->view('app/default/admin/common/css'); ?>

    <style>
        .overlay::after,
        .overlay-content {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            width: 100%;
        }

        .overlay::after {
            content: "";
            bottom: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.5);
        }

        .overlay-content {
            z-index: 1;
        }
    </style>

    <?php
        $this->load->view('app/default/common/head_bottom');
        $this->load->view('app/default/admin/common/menu');
    ?>

    <main class="container">
        <div class="row">
            <div id="j-ar" class="col-sm-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item active">Admin</li>
                </ol>

                <ul class="list-unstyled j-error d-none" data-show-errors></ul>

                <div class="overlay position-relative text-center">
                    <div class="row">
                        <div class="col-sm-6 col-md-3 mb-3">
                            <div class="card">
                                <div class="card-header">Total users</div>
                                <div class="card-body j-utotal">0</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3 mb-3">
                            <div class="card">
                                <div class="card-header">Verified users</div>
                                <div class="card-body j-uverified">0</div>
                            </div>
                        </div>

                        <div class="w-100 d-md-none"></div>

                        <div class="col-sm-6 col-md-3 mb-3">
                            <div class="card">
                                <div class="card-header">Unverified users</div>
                                <div class="card-body j-uunverified">0</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3 mb-3">
                            <div class="card">
                                <div class="card-header">Recent user</div>
                                <div class="card-body j-urecent">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center j-loading overlay-content">
                        <p><img src="images/loader.gif" alt="loading"></p>
                        <p>Loading</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php $this->load->view('app/default/admin/common/foot_top'); ?>

    <script>
    (function ($) {
        "use strict";

        var gform, jar, callbacksQueue;

        function processQueue() {
            if (callbacksQueue.length) {
                setTimeout(function () {
                    callbacksQueue.pop()();
                }, 1000);
            } else {
                $(".j-loading", jar).addClass("d-none");
                $(".overlay", jar).removeClass("overlay");
            }
        }

        function getRecentUser() {
            gform.submit({
                data: {
                    "j-af": "r",
                    action: "getrecentuser"
                },
                success: function (rdata) {
                    $(".j-urecent", jar).text(rdata || "-");
                    processQueue();
                }
            });
        }

        function getUsersTotal() {
            gform.submit({
                data: {
                    "j-af": "r",
                    action: "getuserstotal"
                },
                success: function (rdata) {
                    var total = 0;

                    if (Number(rdata[0])) {
                        total += Number(rdata[0]);
                        $(".j-uunverified", jar).text(rdata[0]);
                    }

                    if (Number(rdata[1])) {
                        total += Number(rdata[1]);
                        $(".j-uverified", jar).text(rdata[1]);
                    }

                    $(".j-utotal", jar).text(total);
                    processQueue();
                }
            });
        }

        function init() {
            $ = jQuery;
            gform = new GForm();
            jar = $("#j-ar");

            callbacksQueue = [
                getUsersTotal,
                getRecentUser
            ];

            callbacksQueue.reverse();
            processQueue();
        }

        (window._jq = window._jq || []).push(init);
    }());
    </script>

    <script type="text/x-async-js" data-src="js/form.js" class="j-ajs"></script>

    <?php
        $this->load->view('app/default/admin/common/js');
        $this->load->view('app/default/common/foot_bottom');
    ?>
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
    <title>Admin - <?php echo config_item('site_name'); ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?php
        $this->load->view('app/default/common/css');
        $this->load->view('app/default/common/head_bottom');
        $this->load->view('app/default/admin/common/menu');
    ?>

    <main class="container">
        <div class="row">
            <div id="j-ar" class="col-md-12">
                <ol class="breadcrumb">
                    <li>You are here: </li>
                    <li><a href="">Home</a></li>
                    <li class="active">Admin</li>
                </ol>

                <ul class="list-unstyled j-error hide"></ul>

                <div data-jitem="loading" class="text-center">
                    <p><img src="images/loader.gif" alt="loading"></p>
                    <p>Loading</p>
                </div>

                <div data-jitem="users" class="clearfix hide">
                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">Total users</div>
                            <div data-jitem="total" class="panel-body text-center">0</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">Verified users</div>
                            <div data-jitem="verified" class="panel-body text-center">0</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">Unverified users</div>
                            <div data-jitem="unverified" class="panel-body text-center">0</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-heading">Recent user</div>
                            <div data-jitem="recent" class="panel-body text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php $this->load->view('app/default/admin/common/foot_top'); ?>

    <script>
    (function ($) {
        "use strict";

        var gform, jar, jitems;

        function getJitem(key, context) {
            var elm;

            if (context || !jitems[key]) {
                elm = $("[data-jitem='" + key + "']", context || jar);

                if (!context) {
                    jitems[key] = elm;
                }
            } else {
                elm = jitems[key];
            }

            return elm;
        }

        function setUserInfo(data) {
            var elm = getJitem("users"),
            total = 0;

            if (Number(data.total[0])) {
                total += Number(data.total[0]);
                getJitem("unverified", elm).text(data.total[0]);
            }

            if (Number(data.total[1])) {
                total += Number(data.total[1]);
                getJitem("verified", elm).text(data.total[1]);
            }

            getJitem("recent", elm).text(data.recent || "-");
            getJitem("total", elm).text(total);
        }

        function getItems() {
            gform.submit({
                "j-af": "r",
                action: "getitems"
            }, function (data) {
                setUserInfo(data.users);
                getJitem("users").removeClass("hide");
            }, function () {
                getJitem("loading").addClass("hide");
            });
        }

        function init() {
            $ = jQuery;
            gform = new GForm();
            jar = $("#j-ar");
            jitems = {};

            gform.init();
            getItems();
        }

        window._jq = [init];
    }());
    </script>

    <script async type="text/x-js" src="js/form.js" class="j-ljs"></script>

    <?php
        $this->load->view('app/default/common/js');
        $this->load->view('app/default/common/foot_bottom');
    ?>
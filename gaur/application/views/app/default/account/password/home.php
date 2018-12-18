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
    <title>Password - <?php echo config_item('site_name'); ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?php
        $this->load->view('app/default/common/css');
        $this->load->view('app/default/common/head_bottom');
        $this->load->view('app/default/common/menu');
    ?>

    <main class="container">
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <div class="card mb-3">
                    <div class="card-header">Password</div>
                    <div class="list-group list-group-flush">
                        <a class="list-group-item list-group-item-action" href="account">Account</a>
                        <a class="list-group-item list-group-item-action active" href="account/password">Password</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 col-md-9">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="account">Account</a></li>
                    <li class="breadcrumb-item active">Password</li>
                </ol>

                <div class="card">
                    <div class="card-header">Change password</div>
                    <div id="j-ar" class="card-body">
                        <ul class="list-unstyled j-error d-none"></ul>
                        <p class="alert alert-success j-success mx-auto d-none"></p>

                        <form method="post" onsubmit="return false">
                            <div class="form-group">
                                <p>Old password <span class="text-danger">*</span></p>
                                <input class="form-control" name="cpassword" type="password" required>
                            </div>
                            <div class="form-group">
                                <p>New password <span class="text-danger">*</span></p>
                                <input class="form-control" name="password" type="password" required>
                            </div>
                            <div class="form-group">
                                <p>Confirm new password <span class="text-danger">*</span></p>
                                <input class="form-control" name="password-confirm" type="password" required>
                            </div>
                            <div>
                                <input name="<?php echo $csrf['name']; ?>" type="hidden" value="<?php echo $csrf['hash']; ?>">
                                <input class="btn btn-primary" type="submit" value="Update">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php $this->load->view('app/default/common/foot_top'); ?>

    <script>
    (function ($) {
        "use strict";

        var gform, jar;

        function submitForm() {
            var uinputs = { "j-af": "s" },
            form = this;

            $("[name]", form).each(function (k, v) {
                uinputs[$(v).attr("name")] = $(v).val();
            });

            gform.submit({
                data: uinputs,
                success: function (msg) {
                    $(".j-success", jar).text(msg).removeClass("d-none");
                    $(form).addClass("d-none");
                }
            });
        }

        function init() {
            $ = jQuery;
            gform = new GForm();
            jar = $("#j-ar");

            $("form", jar).on("submit", submitForm);
        }

        (window._jq = window._jq || []).push(init);
    }());
    </script>

    <script type="text/x-async-js" data-src="js/form.js" class="j-ajs"></script>

    <?php
        $this->load->view('app/default/common/js');
        $this->load->view('app/default/common/foot_bottom');
    ?>
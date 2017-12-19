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
    <title>Create your password - <?php echo config_item('site_name'); ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?php $this->load->view('app/default/common/css'); ?>

    <style>
        .sblock {
            width: 400px;
            max-width: 100%;
        }
    </style>

    <?php
        $this->load->view('app/default/common/head_bottom');
        $this->load->view('app/default/common/menu');
    ?>

    <main class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="j-ar" class="sblock center-block">
                    <h1 class="text-center">Create Password</h1>

                    <p class="alert alert-info j-info">Congratulations! your account has been successfully created. Please enter your password below.</p>

                    <ul class="list-unstyled j-error hide"></ul>
                    <p class="alert alert-success j-success hide"></p>

                    <form method="post" onsubmit="return false">
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <input class="form-control" name="password" type="password" required>
                        </div>

                        <div class="form-group">
                            <label>Password Confirmation <span class="text-danger">*</span></label>
                            <input class="form-control" name="password-confirm" type="password" required>
                        </div>

                        <div class="form-group">
                            <input name="<?php echo $csrf['name']; ?>" type="hidden" value="<?php echo $csrf['hash']; ?>">
                            <input class="btn btn-primary" type="submit" value="Create Password">
                        </div>
                    </form>
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
                uinputs[v.name] = v.value;
            });

            gform.submit({
                data: uinputs,
                success: function (msg) {
                    $(".j-success", jar).text(msg).removeClass("hide");
                    $(form).addClass("hide");

                    setTimeout(function () {
                        location.href = "account/login";
                    }, 5000);
                }
            });
        }

        function init() {
            $ = jQuery;
            gform = new GForm();
            jar = $("#j-ar");

            gform.init();
            $("form", jar).on("submit", submitForm);

            setTimeout(function () {
                $(".j-info").addClass("hide");
            }, 5000);
        }

        window._jq = [init];
    }());
    </script>

    <script async type="text/x-js" src="js/form.js" class="j-ljs"></script>

    <?php
        $this->load->view('app/default/common/js');
        $this->load->view('app/default/common/foot_bottom');
    ?>
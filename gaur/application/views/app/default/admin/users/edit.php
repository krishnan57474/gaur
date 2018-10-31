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
    <title>Edit a user - <?php echo config_item('site_name'); ?></title>

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
        $this->load->view('app/default/admin/common/menu');
    ?>

    <main class="container">
        <div class="row">
            <div class="col-sm-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="admin">Admin</a></li>
                    <li class="breadcrumb-item"><a href="admin/users">Users</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>

                <div id="j-ar" class="sblock m-auto">
                    <ul class="list-unstyled j-error d-none"></ul>
                    <p class="alert alert-success j-success d-none"></p>

                    <form method="post" onsubmit="return false">
                        <div class="form-group">
                            <label>Username <span class="text-danger">*</span></label>
                            <input class="form-control" name="username" type="text" required value="<?php echo hentities($user['username']); ?>">
                            <small class="form-text text-muted">Username can contain letters (a-z) and numbers (0-9).</small>
                        </div>

                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input class="form-control" name="email" type="email" required value="<?php echo hentities($user['email']); ?>">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" name="password" type="password">
                        </div>

                        <div class="form-group">
                            <label>Password Confirmation</label>
                            <input class="form-control" name="password-confirm" type="password">
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option value="0">Disable</option>
                                <option value="1"<?php if ($user['status']): ?> selected<?php endif; ?>>Enable</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <input name="<?php echo $csrf['name']; ?>" type="hidden" value="<?php echo $csrf['hash']; ?>">
                            <div class="form-row">
                                <div class="col">
                                    <input class="btn btn-block btn-primary" type="submit" value="Update">
                                </div>
                                <div class="col">
                                    <a class="btn btn-block btn-secondary" href="admin/users">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php $this->load->view('app/default/admin/common/foot_top'); ?>

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
                success: function (rdata) {
                    $(".j-success", jar).text(rdata[0]).removeClass("d-none");
                    $(form).addClass("d-none");

                    setTimeout(function () {
                        location.href = rdata[1];
                    }, 3000);
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
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

$status = array(
    'glyphicon glyphicon-remove text-danger',
    'glyphicon glyphicon-ok text-success'
);

?>
    <title><?php echo hentities($user['username']); ?> - <?php echo config_item('site_name'); ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?php $this->load->view('app/default/common/css'); ?>

    <?php if (!$user['activation']): ?>
    <style>
        .spin {
            -webkit-animation: spin 1s linear infinite;
            -moz-animation: spin 1s linear infinite;
            animation: spin 1s linear infinite;
        }

        @-moz-keyframes spin {
            100% {
                -moz-transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spin {
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>
    <?php endif; ?>

    <?php
        $this->load->view('app/default/common/head_bottom');
        $this->load->view('app/default/admin/common/menu');
    ?>

    <main class="container">
        <div class="row">
            <div id="j-ar" class="col-md-12">
                <div class="clearfix">
                    <ol class="breadcrumb pull-left">
                        <li> You are here: </li>
                        <li><a href="">Home</a></li>
                        <li><a href="admin">Admin</a></li>
                        <li><a href="admin/users">Users</a></li>
                        <li class="active">View</li>
                    </ol>
                    <div class="form-group pull-right">
                        <?php if (!$user['activation']): ?>
                        <span class="btn btn-primary j-resend" title="resend email verification">
                            <span class="glyphicon glyphicon-repeat"></span>
                            Resend
                        </span>
                        <?php endif; ?>
                        <a href="admin/users/edit/<?php echo $user['id']; ?>" class="btn btn-success">
                            <span class="glyphicon glyphicon-pencil"></span>
                            Edit
                        </a>
                        <a href="admin/users" class="btn btn-default">
                            <span class="glyphicon glyphicon-arrow-left"></span>
                            Back
                        </a>
                    </div>
                </div>

                <?php if (!$user['activation']): ?>
                <ul class="list-unstyled j-error hide"></ul>
                <p class="alert alert-success j-success hide"></p>

                <p class="alert alert-info">
                    <span class="glyphicon glyphicon-exclamation-sign"></span>
                    To resend the verification email, click Resend button.
                </p>
                <?php endif; ?>

                <table class="table">
                    <tr>
                        <td>ID</td>
                        <td><?php echo $user['id']; ?></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><?php echo hentities($user['username']); ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php echo hentities($user['email']); ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><span class="<?php echo $status[$user['status']]; ?>"></span></td>
                    </tr>
                    <tr>
                        <td>Email verified</td>
                        <td><span class="<?php echo $status[$user['activation']]; ?>"></span></td>
                    </tr>
                    <tr>
                        <td>is Admin</td>
                        <td><span class="<?php echo $status[$user['admin']]; ?>"></span></td>
                    </tr>
                    <tr>
                        <td>Date added</td>
                        <td><?php echo $user['date_added']; ?> (<?php echo datetime_diff($user['date_added'], date('Y-m-d H:i:s')); ?> ago)</td>
                    </tr>
                    <tr>
                        <td>Last visited</td>
                        <td>
                            <?php if (preg_match('#[1-9]#', $user['last_visited'])): ?>
                            <?php echo $user['last_visited']; ?> (<?php echo datetime_diff($user['last_visited'], date('Y-m-d H:i:s')); ?> ago)
                            <?php else: echo '-'; endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </main>

    <?php $this->load->view('app/default/admin/common/foot_top'); ?>

    <?php if (!$user['activation']): ?>
    <script>
    (function ($) {
        "use strict";

        var gform, jar;

        function sendVerification() {
            var btn = $(this);

            $("span", btn).addClass("glyphicon-refresh spin");

            gform.submit({
                data: { "j-af": "s" },
                success: function (msg) {
                    $(".j-success", jar).text(msg).removeClass("hide");
                    btn.addClass("hide");
                },
                load: function () {
                    $("span", btn).removeClass("glyphicon-refresh spin");
                }
            });
        }

        function init() {
            $ = jQuery;
            gform = new GForm();
            jar = $("#j-ar");

            gform.init();
            $(".j-resend", jar).on("click", sendVerification);
        }

        window._jq = [init];
    }());
    </script>

    <script async type="text/x-js" src="js/form.js" class="j-ljs"></script>
    <?php endif; ?>

    <?php
        $this->load->view('app/default/common/js');
        $this->load->view('app/default/common/foot_bottom');
    ?>
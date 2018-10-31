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

$status = array(
    'oi oi-x text-danger',
    'oi oi-check text-success'
);

?>
    <title>View a user - <?php echo config_item('site_name'); ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?php $this->load->view('app/default/admin/common/css'); ?>

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

    <main id="j-ar" class="container">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="admin">Admin</a></li>
                    <li class="breadcrumb-item"><a href="admin/users">Users</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
            <div class="col-sm-6 mb-2">
                <div class="row no-gutters">
                    <?php if (!$user['activation']): ?>
                    <div class="col offset-lg-3 mb-2 mr-1">
                        <button type="button" class="btn btn-block btn-primary j-resend" title="Resend email verification">
                            <span class="oi oi-reload"></span>
                            Resend
                        </button>
                    </div>
                    <?php endif; ?>
                    <div class="col mb-2 mr-1<?php if ($user['activation']): ?> offset-lg-6<?php endif; ?>">
                        <a href="admin/users/edit/<?php echo $user['id']; ?>" class="btn btn-block btn-success">
                            <span class="oi oi-pencil"></span>
                            Edit
                        </a>
                    </div>
                    <div class="col mb-2">
                        <a href="admin/users" class="btn btn-block btn-secondary">
                            <span class="oi oi-arrow-left"></span>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <?php if (!$user['activation']): ?>
                <ul class="list-unstyled j-error d-none"></ul>
                <p class="alert alert-success j-success d-none"></p>

                <p class="alert alert-info">
                    To resend the verification email, click Resend button.
                </p>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">User details</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4">ID</div>
                            <div class="col-8"><?php echo $user['id']; ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Username</div>
                            <div class="col-8"><?php echo hentities($user['username']); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Email</div>
                            <div class="col-8"><?php echo hentities($user['email']); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Status</div>
                            <div class="col-8"><span class="<?php echo $status[$user['status']]; ?>"></span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Email verified</div>
                            <div class="col-8"><span class="<?php echo $status[$user['activation']]; ?>"></span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">is Admin</div>
                            <div class="col-8"><span class="<?php echo $status[$user['admin']]; ?>"></span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Date added</div>
                            <div class="col-8"><?php echo date('d-m-Y h:i a', strtotime($user['date_added'])); ?> (<?php echo datetime_diff($user['date_added'], date('Y-m-d H:i:s')); ?> ago)</div>
                        </div>
                        <div class="row">
                            <div class="col-4">Last visited</div>
                            <div class="col-8">
                                <?php if (preg_match('#[1-9]#', $user['last_visited'])): ?>
                                <?php echo date('d-m-Y h:i a', strtotime($user['last_visited'])); ?> (<?php echo datetime_diff($user['last_visited'], date('Y-m-d H:i:s')); ?> ago)
                                <?php else: echo '-'; endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
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

            $("span", btn).addClass("oi-loop-circular spin").removeClass("oi-reload");

            gform.submit({
                data: { "j-af": "s" },
                success: function (msg) {
                    $(".j-success", jar).text(msg).removeClass("d-none");
                    btn.addClass("d-none");
                },
                load: function () {
                    $("span", btn).removeClass("oi-loop-circular spin").addClass("oi-reload");
                }
            });
        }

        function init() {
            $ = jQuery;
            gform = new GForm();
            jar = $("#j-ar");

            $(".j-resend", jar).on("click", sendVerification);
        }

        (window._jq = window._jq || []).push(init);
    }());
    </script>

    <script type="text/x-async-js" data-src="js/form.js" class="j-ajs"></script>
    <?php endif; ?>

    <?php
        $this->load->view('app/default/admin/common/js');
        $this->load->view('app/default/common/foot_bottom');
    ?>
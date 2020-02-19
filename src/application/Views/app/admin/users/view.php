<?php

$status = [
    'fas fa-times text-danger',
    'fas fa-check text-success'
];

?>
    <?= view('app/admin/common/head_top') ?>

    <title>View a user - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/admin/common/css') ?>
    <?= view('app/admin/common/head_bottom') ?>
    <?= view('app/admin/common/menu') ?>

    <main class="container mb-3" id="j-ar">
        <div class="row align-items-center">
            <div class="col-md-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="admin">Admin</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="admin/users">Users</a>
                    </li>
                    <li class="breadcrumb-item active">
                        View
                    </li>
                </ol>
            </div>
            <div class="col-md-6 mb-2">
                <div class="form-row row-cols-2 row-cols-sm-3 row-cols-lg-4 justify-content-end">
                    <?php if (!$user['activation']): ?>
                    <div class="col mb-2">
                        <button type="button" class="btn btn-block btn-success j-confirm-toggle">
                            <span class="fas fa-check"></span>
                            Activate
                        </button>
                    </div>
                    <?php endif; ?>

                    <div class="col mb-2">
                        <a href="admin/users/edit/<?= $user['id'] ?>" class="btn btn-block btn-primary">
                            <span class="fas fa-edit"></span>
                            Edit
                        </a>
                    </div>
                    <div class="col mb-2">
                        <a href="admin/users" class="btn btn-block btn-secondary">
                            <span class="fas fa-long-arrow-alt-left"></span>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!$user['activation']): ?>
        <div class="alert alert-warning text-center text-sm-left d-none j-confirm">
            <div class="row align-items-center">
                <div class="col-sm-6 mb-2 mb-sm-0">Confirm change activation</div>
                <div class="col-sm-6 text-sm-right">
                    <button type="button" class="btn btn-success j-confirm-change">Confirm</button>
                    <button type="button" class="btn btn-secondary j-confirm-toggle">Cancel</button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">User details</div>
                    <div class="card-body pb-0">
                        <div class="row mb-3">
                            <div class="col-4">ID</div>
                            <div class="col-8"><?= $user['id'] ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Username</div>
                            <div class="col-8"><?= hentities($user['username']) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Email</div>
                            <div class="col-8"><?= hentities($user['email']) ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Status</div>
                            <div class="col-8"><span class="<?= $status[$user['status']] ?>"></span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Activation</div>
                            <div class="col-8"><span class="<?= $status[$user['activation']] ?>"></span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Admin</div>
                            <div class="col-8"><span class="<?= $status[$user['admin']] ?>"></span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4">Date added</div>
                            <div class="col-8"><?= date('d-m-Y h:i a', strtotime($user['date_added'])) ?></div>
                        </div>

                        <?php if ($user['last_visited']): ?>
                        <div class="row mb-3">
                            <div class="col-4">Last visited</div>
                            <div class="col-8"><?= date('d-m-Y h:i a', strtotime($user['last_visited'])) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?= view('app/admin/common/foot_top') ?>

    <?php if (!$user['activation']): ?>
    <script nonce="<?= getCspNonce() ?>">
    (function ($) {
        "use strict";

        var gform, jar;

        function confirm() {
            $(".j-confirm-change", jar).on("click", function () {
                gform.submit({
                    context: jar,
                    data: {
                        action: "activate",
                        "j-ar": "r"
                    },
                    success: function () {
                        location.reload();
                    }
                });
            });
        }

        function confirmToggle() {
            $(".j-confirm-toggle", jar).on("click", function () {
                $(".j-confirm", jar).toggleClass("d-none");
            });
        }

        function init() {
            $ = jQuery;
            gform = GForm();
            jar = $("#j-ar");

            confirmToggle();
            confirm();
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

    <script type="text/x-async-js" data-src="js/form.js" class="j-ajs"></script>
    <?php endif; ?>

    <?= view('app/admin/common/js') ?>
    <?= view('app/admin/common/foot_bottom') ?>

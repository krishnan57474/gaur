    <?= view('app/default/common/head_top') ?>

    <title>Edit a user - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container" id="j-ar">
        <div class="row">
            <div class="col-sm-12">
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
                        Edit
                    </li>
                </ol>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <ul class="list-unstyled j-error d-none"></ul>
                <p class="alert alert-success j-success d-none"></p>

                <form method="post">
                    <div class="form-group">
                        <label>Username <span class="text-danger">*</span></label>
                        <input class="form-control" name="username" type="text" required value="<?= hentities($user['username']) ?>">
                        <small class="form-text text-muted">Username can contain letters (a-z) and numbers (0-9).</small>
                    </div>

                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input class="form-control" name="email" type="email" required value="<?= hentities($user['email']) ?>">
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
                        <label>Admin <span class="text-danger">*</span></label>
                        <select class="form-control" name="admin" required>
                            <option value="">Choose</option>
                            <option value="0"<?php if (!$user['admin']): ?> selected<?php endif; ?>>Disable</option>
                            <option value="1"<?php if ($user['admin']): ?> selected<?php endif; ?>>Enable</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="">Choose</option>
                            <option value="0"<?php if (!$user['status']): ?> selected<?php endif; ?>>Disable</option>
                            <option value="1"<?php if ($user['status']): ?> selected<?php endif; ?>>Enable</option>
                        </select>
                    </div>

                    <div class="form-row form-group">
                        <div class="col">
                            <input name="<?= $csrf['name'] ?>" type="hidden" value="<?= $csrf['hash'] ?>">
                            <input class="btn btn-block btn-primary" type="submit" value="Update">
                        </div>
                        <div class="col">
                            <a class="btn btn-block btn-secondary" href="admin/users">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?= view('app/default/common/foot_top') ?>

    <script nonce="<?= getCspNonce() ?>">
    (function ($) {
        "use strict";

        var gform, jar;

        function submitForm(form) {
            var uinputs = {
                action: "submit",
                "j-ar": "r"
            };

            $("[name]", form).each(function (k, v) {
                uinputs[$(v).attr("name")] = $(v).val();
            });

            gform.submit({
                context: jar,
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
            gform = GForm();
            jar = $("#j-ar");

            $("form", jar).on("submit", function (e) {
                e.preventDefault();
                submitForm(e.target);
            });
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

    <script type="text/x-async-js" data-src="js/form.js" class="j-ajs"></script>

    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>

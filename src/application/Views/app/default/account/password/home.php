    <?= view('app/default/common/head_top') ?>

    <title>Password - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container mb-3">
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <?= view('app/default/common/account_menu') ?>
            </div>
            <div class="col-sm-8 col-md-9">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="account">Account</a></li>
                    <li class="breadcrumb-item active">Password</li>
                </ol>

                <div class="card">
                    <div class="card-header">Change password</div>
                    <div class="card-body pb-0" id="j-ar">
                        <ul class="list-unstyled j-error d-none"></ul>
                        <p class="alert alert-success j-success d-none"></p>

                        <form method="post">
                            <div class="form-group">
                                <label>Old password <span class="text-danger">*</span></label>
                                <input class="form-control" name="password-current" type="password" required>
                            </div>
                            <div class="form-group">
                                <label>New password <span class="text-danger">*</span></label>
                                <input class="form-control" name="password-new" type="password" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm new password <span class="text-danger">*</span></label>
                                <input class="form-control" name="password-confirm" type="password" required>
                            </div>
                            <div class="form-group">
                                <input name="<?= $csrf['name'] ?>" type="hidden" value="<?= $csrf['hash'] ?>">
                                <input class="btn btn-primary" type="submit" value="Update">
                            </div>
                        </form>
                    </div>
                </div>
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
                success: function (msg) {
                    $(".j-success", jar).text(msg).removeClass("d-none");
                    $(form).addClass("d-none");
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

    <?= view('app/default/common/head_top') ?>

    <title>Log in to your account - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container" id="j-ar">
        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <h1 class="text-center">Login</h1>

                <ul class="list-unstyled j-error d-none"></ul>
                <p class="alert alert-success j-success d-none"></p>

                <form method="post">
                    <div class="form-group">
                        <label>Username / Email <span class="text-danger">*</span></label>
                        <input class="form-control" name="identity" type="text" required>
                    </div>

                    <div class="form-group">
                        <label>Password <span class="text-danger">*</span></label>
                        <input class="form-control" name="password" type="password" required>
                    </div>

                    <div class="form-row align-items-center form-group">
                        <div class="col-5">
                            <input name="<?= $csrf['name'] ?>" type="hidden" value="<?= $csrf['hash'] ?>">
                            <input class="btn btn-block btn-primary" type="submit" value="Sign In">
                        </div>
                        <div class="col-7 text-right">
                            <span class="oi oi-lock-unlocked"></span>
                            <a href="account/password/forgot">Forgot Password</a>
                        </div>
                    </div>

                    <p class="text-center">
                        <a href="account/register">Create an account</a>
                    </p>
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
                    }, 1000);
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

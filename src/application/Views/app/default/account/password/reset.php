    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('App\Data\Security\CspConfig', true) ?>">

    <title>Reset your password - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container" id="j-ar">
        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <h1 class="text-center">Reset Password</h1>

                <ul class="list-unstyled j-error d-none" data-show-errors></ul>
                <p class="alert alert-success j-success d-none"></p>

                <div class="text-center j-loading mt-3">
                    <div class="spinner-border text-secondary mb-3"></div>
                    <p>Please wait while we verify your request.</p>
                </div>
            </div>
        </div>
    </main>

    <?= view('app/default/common/foot_top') ?>

    <script nonce="<?= getCspNonce() ?>">
    (function ($) {
        "use strict";

        var gform, jar;

        function validateToken() {
            gform.submit({
                context: jar,
                data: {
                    action: "validate",
                    "j-ar": "r"
                },
                load: function () {
                    $(".j-loading", jar).addClass("d-none");
                },
                success: function (rdata) {
                    $(".j-success", jar).text(rdata[0]).removeClass("d-none");

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

            validateToken();
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

    <script type="text/x-async-js" data-src="js/form.js" class="j-ajs"></script>

    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>

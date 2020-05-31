    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

    <title>Account activation - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container" id="j-ar" data-method="post" data-url="account/email/activate/<?= $token ?>">
        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <h1 class="text-center">Account Activation</h1>

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
    (() => {
        "use strict";

        let $, gform, jar;

        function validateToken() {
            gform.request($(jar).attr("data-method") || $(jar).attr("method"), $(jar).attr("data-url"))
                .on("progress", gform.progress)
                .send()
                .then((response) => {
                    const {errors, data} = response;

                    $(".j-loading", jar).addClass("d-none");

                    if (errors) {
                        gform.error(errors, jar);
                        return;
                    }

                    $(".j-success", jar).text(data.message).removeClass("d-none");

                    setTimeout(() => {
                        location.href = data.link;
                    }, 1000);
                });
        }

        function init() {
            $ = jQuery;
            gform = new GForm();
            jar = document.querySelector("#j-ar");

            validateToken();
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

    <script type="text/x-async-js" data-src="js/form.js" data-type="module" class="j-ajs"></script>

    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>

    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

    <title>Create your password - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container" id="j-ar">
        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <h1 class="text-center">Create Password</h1>

                <p class="alert alert-info j-info">Congratulations! your account has been successfully created. Please enter your password below.</p>

                <ul class="list-unstyled j-error d-none"></ul>
                <p class="alert alert-success j-success d-none"></p>

                <form method="post" data-url="account/password/create" data-timeout="5000">
                    <div class="form-group">
                        <label>Password <span class="text-danger">*</span></label>
                        <input class="form-control" name="password" type="password" required>
                    </div>

                    <div class="form-group">
                        <label>Password Confirmation <span class="text-danger">*</span></label>
                        <input class="form-control" name="password-confirm" type="password" required>
                    </div>

                    <div class="form-group">
                        <input name="<?= $csrf['name'] ?>" type="hidden" value="<?= $csrf['hash'] ?>">
                        <input class="btn btn-primary" type="submit" value="Create Password">
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?= view('app/default/common/foot_top') ?>
    <?= view('app/default/common/js/form') ?>

    <script nonce="<?= getCspNonce() ?>">
    (() => {
        "use strict";

        let $, jar;

        function init() {
            $ = jQuery;
            jar = document.querySelector("#j-ar");

            setTimeout(() => {
                $(".j-info", jar).addClass("d-none");
            }, 5000);
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>

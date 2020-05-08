    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

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
                            <span class="fas fa-unlock"></span>
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
    (() => {
        "use strict";

        let $, gform, jar;

        function submitForm(form) {
            const uinputs = {};

            for (const elm of $("[name]", form).toArray()) {
                uinputs[$(elm).attr("name")] = $(elm).val();
            }

            gform.request("post", "account/login")
                .data(uinputs)
                .on("progress", gform.progress)
                .send()
                .then((response) => {
                    const {errors, data} = response;

                    if (errors) {
                        gform.error(errors, jar[0]);
                        return;
                    }

                    $(".j-success", jar).text(data.message).removeClass("d-none");
                    $(form).addClass("d-none");

                    setTimeout(() => {
                        location.href = data.link;
                    }, 1000);
                });
        }

        function init() {
            $ = jQuery;
            gform = new GForm();
            jar = $("#j-ar");

            $("form", jar).on("submit", (e) => {
                e.preventDefault();
                submitForm(e.target);
            });
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

    <script type="text/x-async-js" data-src="js/form.js" data-type="module" class="j-ajs"></script>

    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>

    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

    <title>Create your account - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <meta name="robots" content="noindex">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container" id="j-ar">
        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <h1 class="text-center">Create an account</h1>

                <ul class="list-unstyled j-error d-none"></ul>
                <p class="alert alert-success j-success d-none"></p>
                <p class="alert alert-warning j-warning d-none"></p>

                <form method="post" data-url="account/register">
                    <div class="form-group">
                        <label>Username <span class="text-danger">*</span></label>
                        <input class="form-control" name="username" type="text" required>
                        <small class="form-text text-muted">Username can contain letters (a-z) and numbers (0-9).</small>
                    </div>

                    <div class="form-group">
                        <label>Email address <span class="text-danger">*</span></label>
                        <input class="form-control" name="email" type="email" required>
                    </div>

                    <div class="form-row align-items-center form-group">
                        <div class="col-5">
                            <input name="<?= $csrf['name'] ?>" type="hidden" value="<?= $csrf['hash'] ?>">
                            <input class="btn btn-block btn-primary" type="submit" value="Sign up">
                        </div>
                        <div class="col-7 text-sm-right">
                            <span class="fas fa-sign-in-alt"></span>
                            <a href="account/login">Already have an account</a>
                        </div>
                    </div>

                    <p class="text-center">
                        <a href="account/activate/resend">Resend activation</a>
                    </p>

                    <p>By registering you confirm that you accept the <a href="">privacy policy</a></p>
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

            gform.request(form.attr("data-method") || form.attr("method"), form.attr("data-url"))
                .data(uinputs)
                .on("progress", gform.progress)
                .send()
                .then((response) => {
                    const {errors, data} = response;

                    if (errors) {
                        gform.error(errors, jar);
                        return;
                    }

                    $(".j-success", jar).text(data.message[0]).removeClass("d-none");
                    $(".j-warning", jar).text(data.message[1]).removeClass("d-none");
                    form.addClass("d-none");
                });
        }

        function init() {
            $ = jQuery;
            gform = new GForm();
            jar = document.querySelector("#j-ar");

            $("form", jar).on("submit", (e) => {
                e.preventDefault();
                submitForm($(e.target));
            });
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

    <script type="text/x-async-js" data-src="js/form.js" data-type="module" class="j-ajs"></script>

    <?= view('app/default/common/js') ?>
    <?= view('app/default/common/foot_bottom') ?>

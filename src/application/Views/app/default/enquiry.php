    <?= view('app/default/common/head_top') ?>

    <title>Enquiry - <?= config('Config\App')->siteName ?></title>

    <!-- meta for search engines -->
    <link rel="canonical" href="<?= config('Config\App')->baseURL ?>enquiry">
    <meta name="robots" content="follow, index">

    <?= view('app/default/common/css') ?>
    <?= view('app/default/common/head_bottom') ?>
    <?= view('app/default/common/menu') ?>

    <main class="container" id="j-ar">
        <div class="row justify-content-center">
            <div class="col-sm-9 col-md-7 col-lg-5">
                <h1 class="text-center">Enquiry</h1>

                <ul class="list-unstyled j-error d-none"></ul>
                <p class="alert alert-success j-success d-none"></p>

                <form method="post">
                    <div class="form-group">
                        <label>Name <span class="text-danger">*</span></label>
                        <input class="form-control" name="name" type="text" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address <span class="text-danger">*</span></label>
                        <input class="form-control" name="email" type="email" required>
                    </div>

                    <div class="form-group">
                        <label>Phone Number <span class="text-danger">*</span></label>
                        <input class="form-control" name="phone" type="text" required>
                    </div>

                    <div class="form-group">
                        <label>Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="message" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Attachment <span class="text-danger">*</span></label>
                        <input class="form-control-file" data-name="attach" data-size="10MB" data-types="jpeg,jpg,png" type="file" required>
                        <small class="form-text text-muted">Maximum upload file size 10MB. Allowed file types jpeg, jpg, png.</small>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="<?= $csrf['name'] ?>" value="<?= $csrf['hash'] ?>">
                        <input class="btn btn-primary" type="submit" value="Submit">
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

        function getFiles(form) {
            var error = false,
            files = {},
            uconfig;

            $("input[type=file]", form).each(function (k, elm) {
                elm = $(elm);

                if (!elm.attr("data-name") || !elm.prop("files").length) {
                    return;
                }

                uconfig = {
                    context: jar,
                    size: elm.attr("data-size"),
                    types: elm.attr("data-types").split(",")
                };

                $(elm.prop("files")).each(function (fk, file) {
                    uconfig.file = file;

                    if (!gform.isValidFile(uconfig)) {
                        error = true;
                        return false;
                    }

                    fk = elm.attr("data-index") || fk;
                    files[elm.attr("data-name") + "[" + fk + "]"] = file;
                });

                if (error) {
                    return false;
                }
            });

            return error ? false : files;
        }

        function submitForm(form) {
            var files = getFiles(form),
            uinputs = new FormData();

            if (!files) {
                return;
            }

            uinputs.set("action", "submit");
            uinputs.set("j-ar", "r");

            $("[name]", form).each(function (k, v) {
                uinputs.set($(v).attr("name"), $(v).val());
            });

            Object.keys(files).forEach(function (k) {
                uinputs.set(k, files[k]);
            });

            gform.submit({
                context: jar,
                data: uinputs,
                success: function (msg) {
                    $(".j-success", jar).text(msg).removeClass("d-none");
                    $(form).addClass("d-none");
                },
                upload: true
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
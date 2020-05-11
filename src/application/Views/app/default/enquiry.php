    <?= view('app/default/common/head_top') ?>

    <meta http-equiv="Content-Security-Policy" content="<?= getCsp('Config', true) ?>">

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
    (() => {
        "use strict";

        let $, gform, jar;

        function getFiles(form) {
            const files = {};
            let uconfig, i, fk;

            for (let elm of $("input[type=file]", form).toArray()) {
                i = 0;
                elm = $(elm);

                if (!elm.attr("data-name") || !elm.prop("files").length) {
                    continue;
                }

                uconfig = {
                    error: (e) => gform.error(e, jar),
                    size: elm.attr("data-size"),
                    types: elm.attr("data-types").split(",")
                };

                for (const file of Array.from(elm.prop("files"))) {
                    uconfig.file = file;

                    if (!gform.isValidFile(uconfig)) {
                        return false;
                    }

                    fk = elm.attr("data-index") || i;
                    files[elm.attr("data-name") + "[" + fk + "]"] = file;
                    i += 1;
                }
            }

            return files;
        }

        function submitForm(form) {
            const uinputs = {},
            files = getFiles(form);

            if (!files) {
                return;
            }

            for (const elm of $("[name]", form).toArray()) {
                uinputs[$(elm).attr("name")] = $(elm).val();
            }

            for (const k of Object.keys(files)) {
                uinputs[k] = files[k];
            }

            gform.request("post", "enquiry")
                .data(uinputs)
                .on("progress", gform.progress)
                .upload(true)
                .send()
                .then((response) => {
                    const {errors, data} = response;

                    if (errors) {
                        gform.error(errors, jar);
                        return;
                    }

                    $(".j-success", jar).text(data.message).removeClass("d-none");
                    $(form).addClass("d-none");
                });
        }

        function init() {
            $ = jQuery;
            gform = new GForm();
            jar = document.querySelector("#j-ar");

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

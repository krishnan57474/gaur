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

            gform.request(form.attr("data-method") || form.attr("method"), form.attr("data-url"))
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
                    form.addClass("d-none");

                    if (typeof data.link === "string") {
                        setTimeout(() => {
                            location.href = data.link;
                        }, Number(form.attr("data-timeout")));
                    }
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

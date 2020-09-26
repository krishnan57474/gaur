    <script nonce="<?= getCspNonce() ?>">
    (() => {
        "use strict";

        let $, gform, jar;

        function assembleInputs(uinputs) {
            const inputs = {};
            let key;

            Object.keys(uinputs)
                .filter(k => k.indexOf(".") > 0)
                .map(k => k.split(".")[0])
                .filter((k, i, o) => o.indexOf(k) === i)
                .forEach(k => inputs[k] = {});

            for (const [k, v] of Object.entries(uinputs)) {
                delete uinputs[k];

                if (k.indexOf(".") > 0) {
                    key = k.split(".");

                    inputs[key[0]][key[1]] = v;
                } else {
                    inputs[k] = v;
                }
            }

            Object.assign(uinputs, inputs);

            return inputs;
        }

        function submitForm(form) {
            const uinputs = {};

            for (const elm of $("[name]", form).toArray()) {
                uinputs[$(elm).attr("name")] = $(elm).val();
            }

            assembleInputs(uinputs);

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

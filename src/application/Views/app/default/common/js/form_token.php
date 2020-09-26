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

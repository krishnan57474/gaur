    <div class="card mb-3">
        <div class="list-group list-group-flush j-account-menu">
            <a class="list-group-item list-group-item-action" href="account">Account</a>
            <a class="list-group-item list-group-item-action" href="account/password">Password</a>
        </div>
    </div>

    <script nonce="<?= getCspNonce() ?>">
    (function ($) {
        "use strict";

        function setActiveMenu() {
            var base = location.href.replace(document.baseURI, ""),
            current;

            $(".j-account-menu > a").each(function (k, elm) {
                current = $(elm).attr("href");

                if (current === undefined) {
                    return;
                }

                if (base === current) {
                    $(elm).addClass("active");
                    return false;
                }
            });
        }

        function init() {
            $ = jQuery;
            setActiveMenu();
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

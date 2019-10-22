    <div class="card mb-3 j-list-menu">
        <div class="card-header d-flex d-md-none justify-content-between j-list-menu-toggle">
            <div class="j-list-menu-title">&nbsp;</div>
            <span class="oi oi-menu"></span>
        </div>

        <div class="list-group list-group-flush d-none d-md-block j-list-menu-list">
            <a class="list-group-item list-group-item-action" href="account">Account</a>
            <a class="list-group-item list-group-item-action" href="account/password">Password</a>
        </div>
    </div>

    <script nonce="<?= getCspNonce() ?>">
    (function ($) {
        "use strict";

        function initMenuToggle() {
            $(".j-list-menu-toggle").on("click", function () {
                $(this).closest(".j-list-menu").find(".j-list-menu-list").toggleClass("d-none");
            });
        }

        function setMenuTitle(elm) {
            elm.closest(".j-list-menu").find(".j-list-menu-title").text($(elm).text());
        }

        function setActiveMenu() {
            var base = location.href.replace(document.baseURI, ""),
            current;

            $(".j-list-menu-list > a").each(function (k, elm) {
                current = $(elm).attr("href");

                if (current === undefined) {
                    return;
                }

                if (base === current) {
                    $(elm).addClass("active");
                    setMenuTitle($(elm));
                    return false;
                }
            });
        }

        function init() {
            $ = jQuery;
            setActiveMenu();
            initMenuToggle();
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

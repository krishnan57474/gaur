    <div class="card mb-3 j-list-menu">
        <div class="card-header d-flex d-md-none justify-content-between j-list-menu-toggle">
            <div class="j-list-menu-title">&nbsp;</div>
            <span class="fas fa-bars"></span>
        </div>

        <div class="list-group list-group-flush d-none d-md-block j-list-menu-list">
            <a class="list-group-item list-group-item-action" href="account">Account</a>
            <a class="list-group-item list-group-item-action" href="account/password">Password</a>
            <a class="list-group-item list-group-item-action" href="account/email">Email address</a>
        </div>
    </div>

    <script nonce="<?= getCspNonce() ?>">
    (() => {
        "use strict";

        let $;

        function initMenuToggle() {
            $(".j-list-menu-toggle").on("click", (e) => {
                $(e.target).closest(".j-list-menu")
                    .find(".j-list-menu-list")
                    .toggleClass("d-none");
            });
        }

        function setMenuTitle(elm) {
            elm.closest(".j-list-menu")
                .find(".j-list-menu-title")
                .text(elm.text());
        }

        function setActiveMenu() {
            const base = location.href.replace(document.baseURI, "");
            let current;

            for (const elm of $(".j-list-menu-list > a").toArray()) {
                current = $(elm).attr("href");

                if (base === current) {
                    $(elm).addClass("active");
                    setMenuTitle($(elm));
                    break;
                }
            }
        }

        function init() {
            $ = jQuery;
            setActiveMenu();
            initMenuToggle();
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

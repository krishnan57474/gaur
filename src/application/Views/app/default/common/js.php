    <script nonce="<?= getCspNonce() ?>">
    (() => {
        "use strict";

        let $;

        function segment(url) {
            return url.replace(/\/$/, "").split("/");
        }

        function compareSegment(base, current) {
            const l = current.length;
            let i = -1,
            match = false;

            while (++i < l) {
                if (base[i] !== current[i]) {
                    break;
                }

                if ((i + 1) >= l) {
                    match = true;
                    break;
                }
            }

            return match;
        }

        function setActiveMenu() {
            const base = segment(location.href.replace(document.baseURI, ""));
            let current;

            for (const melm of $(".g-main-menu").toArray()) {
                for (const elm of $("> li > a", melm).toArray()) {
                    current = $(elm).attr("href");

                    if (compareSegment(base, segment(current))) {
                        $(elm).addClass("active");
                        return;
                    }
                }
            }
        }

        function initMenu() {
            const inputFrg = $('<input class="g-menu-state" type="checkbox">'),
            labelFrg = $('<label class="g-menu-icon"></label>');
            let i = 1,
            childElm,
            id;

            for (const elm of $(".g-sub-menu").toArray()) {
                childElm = $(elm).parent().children();
                id = "g-menu-state-" + i;
                i += 1;

                childElm.first().before(
                    inputFrg.clone().attr("id", id)
                );

                childElm.last().after(
                    labelFrg.clone().attr("for", id)
                );
            }
        }

        function init() {
            $ = jQuery;
            setActiveMenu();
            initMenu();
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

    <script type="text/x-async-css" data-src="https://cdn.jsdelivr.net/gh/FortAwesome/Font-Awesome@5.14.0/css/all.min.css" data-integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" class="j-acss"></script>

    <script type="text/x-async-js" data-src="https://cdn.jsdelivr.net/gh/jquery/jquery@3.5.1/dist/jquery.min.js" data-integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" data-order="1" class="j-ajs"></script>

    <script async type="module" src="js/script.js"></script>

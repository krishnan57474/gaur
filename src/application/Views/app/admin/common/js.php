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
                id = "menu-state-" + i;
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

    <script type="text/x-async-css" data-src="https://cdn.jsdelivr.net/gh/FortAwesome/Font-Awesome@5.13.0/css/all.min.css" data-integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" class="j-acss"></script>

    <script type="text/x-async-js" data-src="https://cdn.jsdelivr.net/gh/jquery/jquery@3.5.0/dist/jquery.min.js" data-integrity="sha384-LVoNJ6yst/aLxKvxwp6s2GAabqPczfWh6xzm38S/YtjUyZ+3aTKOnD/OJVGYLZDl" data-order="1" class="j-ajs"></script>

    <script async type="module" src="js/script.js"></script>

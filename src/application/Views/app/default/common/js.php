    <script nonce="<?= getCspNonce() ?>">
    (function ($) {
        "use strict";

        function segment(url) {
            return url.replace(/\/$/, "").split("/");
        }

        function compareSegment(base, current) {
            var i = -1,
            l = current.length,
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
            var base = segment(location.href.replace(document.baseURI, "")),
            current;

            $(".g-main-menu").each(function () {
                $("> li > a", this).each(function (k, elm) {
                    current = $(elm).attr("href");

                    if (compareSegment(base, segment(current))) {
                        $(elm).addClass("active");
                        return false;
                    }
                });
            });
        }

        function initMenu() {
            var inputFrg = $('<input class="g-menu-state" type="checkbox">'),
            labelFrg = $('<label class="g-menu-icon"></label>'),
            i = 1,
            childElm,
            id;

            $(".g-sub-menu").each(function () {
                childElm = $(this).parent().children();
                id = "menu-state-" + i;
                i += 1;

                childElm.first().before(
                    inputFrg.clone().attr("id", id)
                );

                childElm.last().after(
                    labelFrg.clone().attr("for", id)
                );
            });
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

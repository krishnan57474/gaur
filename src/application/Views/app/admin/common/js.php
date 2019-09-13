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
                if (base[i] === current[i] && (i + 1) >= l) {
                    match = true;
                    break;
                }
            }

            return match;
        }

        function setActiveMenu() {
            var base = segment(location.href.replace(document.baseURI, "")),
            current;

            $(".g-main-menu > li > a").each(function (k, elm) {
                current = $(elm).attr("href");

                if (current === undefined) {
                    return;
                }

                if (compareSegment(base, segment(current))) {
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

    <script type="text/x-async-css" data-src="https://cdn.jsdelivr.net/gh/iconic/open-iconic@1.1.1/font/css/open-iconic-bootstrap.min.css" data-integrity="sha384-wWci3BOzr88l+HNsAtr3+e5bk9qh5KfjU6gl/rbzfTYdsAVHBEbxB33veLYmFg/a" class="j-acss"></script>

    <script type="text/x-async-js" data-src="https://cdn.jsdelivr.net/gh/jquery/jquery@3.4.1/dist/jquery.min.js" data-integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" data-order="1" class="j-ajs"></script>

    <script async src="js/script.js"></script>

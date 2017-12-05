(function ($) {
    "use strict";

    function segment(url) {
        return url.replace(/\/$/, "").split("/");
    }

    function compareSegment(base, current) {
        var i = -1,
        l = current.length,
        match;

        while (++i < l) {
            if (base[i] === current[i] && (i + 1) >= l) {
                match = 1;
                break;
            }
        }

        return match;
    }

    function setActiveMenu() {
        var base = segment(location.href.replace(document.baseURI, ""));

        $("#sm-menu > li > a").each(function (k, elm) {
            if (compareSegment(base, segment($(elm).attr("href")))) {
                $(elm).addClass("current");
                return false;
            }
        });
    }

    function importFile(fileSrc, callback) {
        var script = document.createElement("script");

        script.onload = function () {
            script.remove();

            if (callback) {
                callback();
            }
        };

        script.src = fileSrc;
        document.head.appendChild(script);
    }

    function asyncQueue() {
        if (window._jq) {
            _jq.forEach(function (callback) {
                callback();
            });

            _jq = null;
        }
    }

    function loadCss() {
        var head = document.head;

        $(".j-lcss").each(function (c, elm) {
            $(head).append($("<link rel='stylesheet' href='" + elm.src + "'>"));
        });
    }

    function loadJs(scripts) {
        importFile(scripts.shift().src, function () {
            scripts.length ? loadJs(scripts) : asyncQueue();
        });
    }

    function loadMenu() {
        importFile("js/smartmenus.js", function () {
            $("#sm-menu").smartmenus({
                subMenusSubOffsetX: 1,
                subMenusSubOffsetY: -8
            });
        });
    }

    function init() {
        $ = jQuery;

        setActiveMenu();
        loadCss();
        loadMenu();

        if ($(".j-ljs").length) {
            loadJs($(".j-ljs").toArray());
        } else {
            asyncQueue();
        }
    }

    importFile(["https://code.jquery.com/jquery-3.2.1.min.js"], init);
}());
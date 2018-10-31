(function () {
    "use strict";

    function forEach(obj, callback) {
        Object.keys(obj).forEach(function (k) {
            callback(obj[k], k, obj);
        });
    }

    function importFile(fileSrc, integrity, callback) {
        var script = document.createElement("script");

        script.onload = function () {
            script.parentNode.removeChild(script);

            if (callback) {
                callback();
            }
        };

        if (integrity) {
            script.integrity = integrity;
            script.crossOrigin = "anonymous";
        }

        script.async = true;
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

    function loadJs(scripts) {
        var elm = scripts.pop();

        importFile(elm.getAttribute("data-src"), elm.getAttribute("data-integrity"), function () {
            scripts.length ? loadJs(scripts) : asyncQueue();
        });
    }

    function loadCss() {
        var head = document.head,
        firstChild = head.childNodes[0],
        link;

        forEach(document.querySelectorAll(".j-acss"), function (elm) {
            link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = elm.getAttribute("data-src");

            if (elm.hasAttribute("data-integrity")) {
                link.integrity = elm.getAttribute("data-integrity");
                link.crossOrigin = "anonymous";
            }

            firstChild = firstChild || head.childNodes[0];
            head.insertBefore(link, firstChild);
        });
    }

    function init() {
        loadCss();

        if (document.querySelector(".j-ajs")) {
            loadJs([].slice.call(document.querySelectorAll(".j-ajs")).reverse());
        } else {
            asyncQueue();
        }
    }

    init();
}());
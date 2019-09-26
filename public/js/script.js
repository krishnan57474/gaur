(function () {
    "use strict";
    var ImportCache = (function () {
        function ImportCache() {
        }
        ImportCache.add = function (url) {
            this.caches.push(url);
        };
        ImportCache.exists = function (url) {
            return this.caches.indexOf(url) > -1;
        };
        ImportCache.reset = function () {
            this.caches = [];
        };
        return ImportCache;
    }());
    var ImportCss = (function () {
        function ImportCss() {
        }
        ImportCss.getFirstChild = function () {
            if (!document.head) {
                document.documentElement.insertBefore(document.createElement("head"), document.documentElement.childNodes[0]);
            }
            if (!document.head.childNodes.length) {
                document.head.appendChild(document.createTextNode(""));
            }
            return document.head.childNodes[0];
        };
        ImportCss.getFrg = function (fileSrc, integrity) {
            var link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = fileSrc;
            if (integrity) {
                link.integrity = integrity;
                link.crossOrigin = "anonymous";
            }
            return link;
        };
        ImportCss.import = function (elm, firstChild) {
            var fileSrc = elm.getAttribute("data-src") || "", integrity = elm.getAttribute("data-integrity") || "";
            if (ImportCache.exists(fileSrc)) {
                return;
            }
            ImportCache.add(fileSrc);
            document.head.insertBefore(this.getFrg(fileSrc, integrity), firstChild);
        };
        ImportCss.init = function () {
            var elmsList = document.querySelectorAll(".j-acss"), firstChild = this.getFirstChild(), length = elmsList.length;
            ImportCache.reset();
            for (var i = 0; i < length; i++) {
                this.import(elmsList[i], firstChild);
            }
        };
        return ImportCss;
    }());
    var ImportJs = (function () {
        function ImportJs() {
        }
        ImportJs.asyncQueue = function () {
            if (!window._jq) {
                return;
            }
            window._jq.forEach(function (callback) { return callback(); });
            window._jq = null;
        };
        ImportJs.getFrg = function (fileSrc, integrity, callback) {
            var script = document.createElement("script");
            script.async = true;
            script.src = fileSrc;
            if (integrity) {
                script.integrity = integrity;
                script.crossOrigin = "anonymous";
            }
            if (callback) {
                script.onload = callback;
            }
            return script;
        };
        ImportJs.getScripts = function () {
            var elmsList = document.querySelectorAll(".j-ajs"), length = elmsList.length, orderedScripts = [], scripts = [], unorderedScripts = [];
            var elm, order;
            for (var i = 0; i < length; i++) {
                elm = elmsList[i];
                order = Number(elm.getAttribute("data-order"));
                if (order) {
                    order -= 1;
                    if (!orderedScripts[order]) {
                        orderedScripts[order] = [];
                    }
                    orderedScripts[order].push(elm);
                }
                else {
                    unorderedScripts.push(elm);
                }
            }
            orderedScripts.forEach(function (e) { return scripts.push.apply(scripts, e); });
            scripts.push.apply(scripts, unorderedScripts);
            scripts.reverse();
            return scripts;
        };
        ImportJs.import = function (elm, scripts) {
            var _this = this;
            var fileSrc = elm.getAttribute("data-src") || "", integrity = elm.getAttribute("data-integrity") || "", skipQueue = elm.hasAttribute("data-skip-queue"), callback = function () { return _this.processQueue(scripts); };
            if (ImportCache.exists(fileSrc)) {
                callback();
                return;
            }
            ImportCache.add(fileSrc);
            document.head.appendChild(this.getFrg(fileSrc, integrity, skipQueue ? undefined : callback));
            if (skipQueue) {
                callback();
            }
        };
        ImportJs.processQueue = function (scripts) {
            var elm = scripts.pop();
            if (elm) {
                this.import(elm, scripts);
            }
            else {
                this.asyncQueue();
            }
        };
        ImportJs.init = function () {
            var scripts = this.getScripts();
            ImportCache.reset();
            this.processQueue(scripts);
        };
        return ImportJs;
    }());
    function init() {
        ImportCss.init();
        ImportJs.init();
    }
    init();
})();

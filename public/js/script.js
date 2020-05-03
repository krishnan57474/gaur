"use strict";
class ImportCache {
    static add(url) {
        this.caches.push(url);
    }
    static exists(url) {
        return this.caches.indexOf(url) > -1;
    }
    static reset() {
        this.caches = [];
    }
}
class ImportCss {
    static getFirstChild() {
        if (!document.head) {
            document.documentElement.insertBefore(document.createElement("head"), document.documentElement.childNodes[0]);
        }
        if (!document.head.childNodes.length) {
            document.head.appendChild(document.createTextNode(""));
        }
        return document.head.childNodes[0];
    }
    static getFrg(fileSrc, integrity) {
        const link = document.createElement("link");
        link.rel = "stylesheet";
        link.href = fileSrc;
        if (integrity) {
            link.integrity = integrity;
            link.crossOrigin = "anonymous";
        }
        return link;
    }
    static import(elm, firstChild) {
        const fileSrc = elm.getAttribute("data-src") || "", integrity = elm.getAttribute("data-integrity") || "";
        if (ImportCache.exists(fileSrc)) {
            return;
        }
        ImportCache.add(fileSrc);
        document.head.insertBefore(this.getFrg(fileSrc, integrity), firstChild);
    }
    static init() {
        const elmsList = document.getElementsByClassName("j-acss"), firstChild = this.getFirstChild();
        ImportCache.reset();
        for (const elm of Array.from(elmsList)) {
            this.import(elm, firstChild);
        }
    }
}
class ImportJs {
    static asyncQueue() {
        if (!Array.isArray(window._jq)) {
            return;
        }
        for (const callback of window._jq) {
            callback();
        }
        window._jq = null;
    }
    static getFrg(fileSrc, integrity, type, callback) {
        const script = document.createElement("script");
        script.async = true;
        script.src = fileSrc;
        if (integrity) {
            script.integrity = integrity;
            script.crossOrigin = "anonymous";
        }
        if (type) {
            script.type = type;
        }
        if (callback) {
            script.onload = callback;
        }
        return script;
    }
    static getScripts() {
        const elmsList = document.getElementsByClassName("j-ajs"), orderedScripts = [], scripts = [], unorderedScripts = [];
        for (const elm of Array.from(elmsList)) {
            let order = Number(elm.getAttribute("data-order"));
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
        for (const elms of orderedScripts) {
            scripts.push(...elms);
        }
        scripts.push(...unorderedScripts);
        return scripts;
    }
    static import(elm) {
        return new Promise((callback) => {
            const fileSrc = elm.getAttribute("data-src") || "", integrity = elm.getAttribute("data-integrity") || "", type = elm.getAttribute("data-type") || "", skipQueue = elm.hasAttribute("data-skip-queue");
            if (ImportCache.exists(fileSrc)) {
                callback();
                return;
            }
            ImportCache.add(fileSrc);
            document.head.appendChild(this.getFrg(fileSrc, integrity, type, skipQueue ? undefined : callback));
            if (skipQueue) {
                callback();
            }
        });
    }
    static async init() {
        const scripts = this.getScripts();
        ImportCache.reset();
        for (const elm of scripts) {
            await this.import(elm);
        }
        this.asyncQueue();
    }
}
function init() {
    ImportCss.init();
    ImportJs.init();
}
init();

"use strict";
class AjaxResponse {
    constructor(xhr) {
        this.raw = xhr;
        Object.defineProperty(this, "raw", {
            value: xhr
        });
    }
    get data() {
        const { response } = this;
        let data = null;
        if (response && response.data) {
            data = response.data;
        }
        Object.defineProperty(this, "data", {
            value: data
        });
        return data;
    }
    get errors() {
        const status = String(this.raw.status)[0];
        let errors = null;
        if (this.isJson && status !== "4" && status !== "5") {
            Object.defineProperty(this, "errors", {
                value: errors
            });
            return errors;
        }
        const { response } = this;
        if (response && Array.isArray(response.errors)) {
            errors = response.errors;
        }
        else {
            errors = [this.raw.statusText];
        }
        Object.defineProperty(this, "errors", {
            value: errors
        });
        return errors;
    }
    get isJson() {
        const isJson = (this.header("Content-Type") || "").split(";").indexOf("application/json") > -1;
        Object.defineProperty(this, "isJson", {
            value: isJson
        });
        return isJson;
    }
    get response() {
        let result = null;
        if (this.isJson && this.raw.response) {
            try {
                result = JSON.parse(this.raw.response);
            }
            catch (e) {
                result = null;
            }
        }
        Object.defineProperty(this, "response", {
            value: result
        });
        return result;
    }
    header(name) {
        return this.raw.getResponseHeader(name);
    }
}
class AjaxRequest {
    constructor(config) {
        this.config = config;
        this.xhr = new XMLHttpRequest();
    }
    encodeUri(data) {
        const uri = [];
        let type;
        for (const [k, item] of Object.entries(data)) {
            type = this.typeOf(item);
            if (type === "Array" || type === "Object") {
                for (const [i, v] of Object.entries(item)) {
                    uri.push(encodeURIComponent(k) + "[" + i + "]" + "=" + encodeURIComponent(String(v)));
                }
            }
            else {
                uri.push(encodeURIComponent(k) + "=" + encodeURIComponent(String(item)));
            }
        }
        return uri.join("&");
    }
    createFormData(data) {
        const formData = new FormData();
        let type;
        for (const [k, item] of Object.entries(data)) {
            type = this.typeOf(item);
            if (type === "Array" || type === "Object") {
                for (const [i, v] of Object.entries(item)) {
                    formData.set(k + "[" + i + "]", this.typeOf(v) === "File" ? v : String(v));
                }
            }
            else {
                formData.set(k, type === "File" ? item : String(item));
            }
        }
        return formData;
    }
    get data() {
        const { config } = this;
        let data = null;
        if (config.method === "GET" ||
            config.method === "HEAD" ||
            !Object.keys(config.data).length) {
            Object.defineProperty(this, "data", {
                value: data
            });
            return null;
        }
        if (config.upload) {
            data = this.createFormData(config.data);
        }
        else {
            data = JSON.stringify(config.data);
        }
        Object.defineProperty(this, "data", {
            value: data
        });
        return data;
    }
    getUrl() {
        const { config } = this;
        let url = config.url;
        if ((config.method === "GET" || config.method === "HEAD") &&
            Object.keys(config.data).length) {
            if (url.indexOf("?") > -1) {
                url += "&";
            }
            else {
                url += "?";
            }
            url += this.encodeUri(config.data);
        }
        return url;
    }
    setHeaders() {
        const { config, xhr } = this, { headers } = config;
        xhr.setRequestHeader("Accept", "application/json");
        if (config.method !== "GET" && config.method !== "HEAD" && typeof this.data === "string") {
            xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
        }
        for (const name of Object.keys(headers)) {
            xhr.setRequestHeader(name, headers[name]);
        }
    }
    typeOf(v) {
        let type = Object.prototype.toString.call(v);
        type = type.substr(8);
        return type.substr(0, type.length - 1);
    }
    open(ignoreLock) {
        const { config, xhr } = this;
        return new Promise((success) => {
            if (!ignoreLock && AjaxRequest.lock) {
                return;
            }
            AjaxRequest.lock = true;
            if (config.events.progress) {
                config.events.progress(true);
            }
            xhr.open(config.method, this.getUrl());
            this.setHeaders();
            xhr.onreadystatechange = () => {
                if (xhr.readyState === xhr.DONE) {
                    if (config.events.progress) {
                        config.events.progress(false);
                    }
                    success(new AjaxResponse(xhr));
                    AjaxRequest.lock = false;
                }
            };
            xhr.send(this.data);
        });
    }
}
class Ajax {
    constructor(method, url, ignoreLock) {
        this.config = {
            data: {},
            events: {},
            headers: {},
            method: method.toUpperCase(),
            upload: false,
            url: ("api/" + url).replace(/\/$/, "")
        };
        this.ignoreLock = ignoreLock;
    }
    data(data) {
        Object.assign(this.config.data, data);
        return this;
    }
    headers(headers) {
        Object.assign(this.config.headers, headers);
        return this;
    }
    on(type, listener) {
        this.config.events[type] = listener;
        return this;
    }
    send() {
        Errors.clear();
        return new AjaxRequest(this.config).open(this.ignoreLock);
    }
    upload(status) {
        this.config.upload = status;
        return this;
    }
}
function getTransitionDuration(elm) {
    const duration = getComputedStyle(elm)
        .getPropertyValue("transition-duration")
        .split(",");
    return parseFloat(duration[0] || "0") * 1000;
}
class ErrorsFrg {
    static getBtnFrg() {
        const btnFrg = document.createElement("button");
        btnFrg.setAttribute("type", "button");
        btnFrg.setAttribute("class", "close");
        btnFrg.setAttribute("data-ebtn", "");
        btnFrg.textContent = "×";
        return btnFrg;
    }
    static getListFrg() {
        const listFrg = document.createElement("li");
        listFrg.setAttribute("class", "alert alert-danger alert-dismissible fade show");
        return listFrg;
    }
    static get(errors, isAutoHide) {
        const errorsFrg = document.createDocumentFragment();
        for (const e of errors) {
            const frgClone = this.listElm.cloneNode(true);
            frgClone.textContent = e;
            if (isAutoHide) {
                frgClone.appendChild(this.btnElm.cloneNode(true));
            }
            else {
                frgClone.classList.remove("alert-dismissible");
            }
            errorsFrg.appendChild(frgClone);
        }
        return errorsFrg;
    }
    static getTransitionDuration() {
        return this.transitionDuration;
    }
    static init() {
        this.btnElm = this.getBtnFrg();
        this.listElm = this.getListFrg();
        this.transitionDuration = getTransitionDuration(this.listElm);
    }
}
class Errors {
    static create(errors, errorElm) {
        const isAutoHide = !errorElm.hasAttribute("data-show-errors");
        errorElm.classList.add("d-none");
        for (const elm of Array.from(errorElm.children)) {
            elm.remove();
        }
        errorElm.appendChild(ErrorsFrg.get(errors, isAutoHide));
        errorElm.classList.remove("d-none");
        if (isAutoHide) {
            this.setAutoHide(errorElm);
        }
        errorElm.scrollIntoView({
            behavior: "smooth",
            block: "end"
        });
    }
    static handler(e) {
        const elm = e.target;
        if (!elm || elm.tagName !== "BUTTON" || !elm.hasAttribute("data-ebtn")) {
            return;
        }
        this.hide(elm.closest(".j-error"));
    }
    static hide(errorElm) {
        if (!errorElm) {
            return;
        }
        for (const elm of Array.from(errorElm.children)) {
            elm.classList.remove("show");
        }
        setTimeout(() => errorElm.classList.add("d-none"), ErrorsFrg.getTransitionDuration());
    }
    static setAutoHide(errorElm) {
        const timer = setTimeout(() => {
            if (!errorElm.classList.contains("d-none")) {
                this.hide(errorElm);
            }
        }, 10000);
        this.errorElms.push(errorElm);
        this.timers.push(timer);
    }
    static clear() {
        for (const t of this.timers.splice(0)) {
            clearTimeout(t);
        }
        for (const elm of this.errorElms.splice(0)) {
            elm.classList.add("d-none");
        }
    }
    static init() {
        ErrorsFrg.init();
        this.errorElms = [];
        this.timers = [];
        addEventListener("click", (e) => this.handler(e));
    }
    static show(errors, context) {
        const errorElm = context.querySelector(".j-error");
        this.clear();
        if (errorElm) {
            this.create(errors, errorElm);
        }
    }
}
class Progress {
    static getProgressBarFrg() {
        const progressBarFrg = document.createElement("div");
        progressBarFrg.setAttribute("class", "progress-bar progress-bar-striped progress-bar-animated");
        return progressBarFrg;
    }
    static getProgressFrg() {
        const progressFrg = document.createElement("div");
        progressFrg.setAttribute("class", "progress fixed-top");
        progressFrg.style.height = "6px";
        progressFrg.style.width = "0";
        return progressFrg;
    }
    static hide() {
        const { progressBarElm, progressElm } = this;
        clearInterval(this.timer);
        progressBarElm.style.width = "100%";
        setTimeout(() => {
            progressBarElm.style.width = "0";
            setTimeout(() => {
                progressElm.style.width = "0";
            }, this.transitionDuration);
        }, 1000);
    }
    static init() {
        this.progressBarElm = this.getProgressBarFrg();
        this.progressElm = this.getProgressFrg();
        this.timer = 0;
        this.transitionDuration = getTransitionDuration(this.progressBarElm);
        this.progressElm.appendChild(this.progressBarElm);
        document.body.appendChild(this.progressElm);
    }
    static show() {
        const { progressBarElm, progressElm } = this;
        let width = 5;
        clearInterval(this.timer);
        progressElm.style.width = "100%";
        progressBarElm.style.width = "0";
        this.timer = setInterval(() => {
            width += 5;
            if (width < 100) {
                progressBarElm.style.width = width + "%";
            }
            else {
                clearInterval(this.timer);
            }
        }, 500);
    }
}
class ValidateFile {
    static toBytes(unit) {
        const units = "bkmgtpezy", size = parseFloat(unit), uprefix = unit.replace(/[^a-zA-Z]+/, "").toLowerCase();
        let uindex = units.indexOf(uprefix[0]);
        if (uindex < 0) {
            uindex = 0;
        }
        return size * Math.pow(1024, uindex);
    }
    static init() {
        this.filesizeError = "The uploaded file exceeds the maximum upload file size limit.";
        this.invalidFileError = "The filetype you are attempting to upload is not allowed.";
    }
    static isValid(args) {
        const { file } = args, rx = new RegExp("^" + args.types.join("$|^") + "$", "i"), ext = file.name.split(".").pop() || "";
        let error = "";
        if (!rx.test(ext) || !file.size) {
            error = this.invalidFileError;
        }
        else if (file.size > this.toBytes(args.size)) {
            error = this.filesizeError;
        }
        if (error && args.error) {
            args.error(error);
        }
        return !error;
    }
}
class Form {
    error(errors, context) {
        Errors.show(Array.isArray(errors) ? errors : [errors], context);
    }
    isValidFile(args) {
        return ValidateFile.isValid(args);
    }
    progress(status) {
        if (status) {
            Progress.show();
        }
        else {
            Progress.hide();
        }
    }
    request(method, url, ignoreLock) {
        return new Ajax(method, url, ignoreLock || false);
    }
}
function gform() {
    return new Form();
}
function init() {
    Errors.init();
    Progress.init();
    ValidateFile.init();
    window.GForm = gform;
}
init();

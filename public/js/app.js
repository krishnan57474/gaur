"use strict";
let configs, gform;
class Jitems {
    static get(key) {
        if (!this.caches[key]) {
            this.caches[key] = Array.from(document.querySelectorAll("[data-jitem='" + key + "']"));
        }
        return this.caches[key][0];
    }
    static getAll(key) {
        if (!this.caches[key]) {
            this.caches[key] = Array.from(document.querySelectorAll("[data-jitem='" + key + "']"));
        }
        return this.caches[key];
    }
    static init() {
        this.caches = Object.create(null);
    }
}
class Confirm {
    static handler(e) {
        const elm = e.target;
        let action = "", callback;
        if (elm.tagName === "BUTTON") {
            action = elm.getAttribute("data-action") || "";
        }
        switch (action) {
            case "confirm":
                callback = this.callback;
                this.hide();
                this.callback = null;
                if (callback) {
                    callback();
                }
                break;
            case "cancel":
                this.hide();
                this.callback = null;
                break;
        }
    }
    static hide() {
        Jitems.get("confirm").classList.add("d-none");
    }
    static add(callback) {
        this.callback = callback;
    }
    static init() {
        Jitems.get("confirm").addEventListener("click", (e) => this.handler(e));
    }
    static show(msg) {
        if (Jitems.get("confirm-msg").textContent !== msg) {
            Jitems.get("confirm-msg").textContent = msg;
        }
        Jitems.get("confirm").classList.remove("d-none");
        Jitems.get("confirm").scrollIntoView({
            behavior: "smooth",
            block: "end"
        });
    }
}
class Status {
    static change(elm) {
        const rowElm = elm.closest(".g-tr"), id = rowElm.getAttribute("data-id") || "0";
        configs.lock = true;
        gform
            .request("post", configs.url + "/" + id + "/status", true)
            .on("progress", gform.progress)
            .send()
            .then((response) => {
            const { errors } = response;
            configs.lock = false;
            if (errors) {
                gform.error(errors, configs.context);
                return;
            }
            this.toggleStatus(elm);
        });
    }
    static handler(e) {
        const elm = e.target;
        if (configs.lock ||
            elm.tagName !== "BUTTON" ||
            elm.getAttribute("data-item") !== "status") {
            return;
        }
        Confirm.add(() => this.change(elm));
        Confirm.show("Confirm change status");
    }
    static toggleStatus(elm) {
        const status = !elm.classList.contains("text-success");
        if (status) {
            elm.classList.add("fa-check", "text-success");
            elm.classList.remove("fa-times", "text-danger");
        }
        else {
            elm.classList.add("fa-times", "text-danger");
            elm.classList.remove("fa-check", "text-success");
        }
    }
    static init() {
        Jitems.get("items").addEventListener("click", (e) => this.handler(e));
    }
}
class PaginationFrg {
    static getBtnFrg() {
        const btnFrg = document.createElement("button");
        btnFrg.setAttribute("type", "button");
        btnFrg.setAttribute("class", "page-link");
        return btnFrg;
    }
    static getItemFrg(page, isActive) {
        const listFrg = this.listElm.cloneNode(true), btnFrg = this.btnElm.cloneNode(true);
        btnFrg.textContent = String(page);
        listFrg.appendChild(btnFrg);
        if (isActive) {
            listFrg.classList.add("active");
        }
        return listFrg;
    }
    static getListFrg() {
        const listFrg = document.createElement("li");
        listFrg.setAttribute("class", "page-item mb-2");
        return listFrg;
    }
    static get(totalPage, currentPage) {
        const frg = document.createDocumentFragment(), sideLinksCount = 2, totalLinks = sideLinksCount * 2;
        let paginationStart = 1, paginationEnd;
        if (currentPage > sideLinksCount) {
            paginationStart = currentPage - sideLinksCount;
        }
        paginationEnd = paginationStart + totalLinks;
        if (paginationEnd > totalPage) {
            paginationStart -= paginationEnd - totalPage;
            if (paginationStart < 1) {
                paginationStart = 1;
            }
            paginationEnd = totalPage;
        }
        if (currentPage > 2) {
            frg.appendChild(this.getItemFrg("Start", false));
        }
        if (currentPage > 1) {
            frg.appendChild(this.getItemFrg("Previous", false));
        }
        paginationStart -= 1;
        while (++paginationStart <= paginationEnd) {
            frg.appendChild(this.getItemFrg(paginationStart, paginationStart === currentPage));
        }
        if (currentPage < totalPage) {
            frg.appendChild(this.getItemFrg("Next", false));
        }
        if (totalPage > totalLinks + 1 && currentPage + 1 < totalPage) {
            frg.appendChild(this.getItemFrg("End", false));
        }
        return frg;
    }
    static init() {
        this.btnElm = this.getBtnFrg();
        this.listElm = this.getListFrg();
    }
}
class Pagination {
    static getPage(page) {
        let currentPage;
        switch (page) {
            case "Start": {
                currentPage = 1;
                break;
            }
            case "Previous": {
                currentPage = configs.currentPage - 1;
                break;
            }
            case "Next": {
                currentPage = configs.currentPage + 1;
                break;
            }
            case "End": {
                currentPage = configs.totalPage;
                break;
            }
            default: {
                currentPage = Number(page);
            }
        }
        if (!currentPage || currentPage < 1) {
            currentPage = 1;
        }
        if (currentPage > configs.totalPage) {
            currentPage = configs.totalPage;
        }
        return currentPage;
    }
    static handler(e) {
        const elm = e.target;
        if (configs.lock || elm.tagName !== "BUTTON") {
            return;
        }
        configs.currentPage = this.getPage(elm.textContent || "");
        Items.get();
    }
    static build() {
        for (const elm of Array.from(Jitems.get("pagination").children)) {
            elm.remove();
        }
        if (configs.totalPage > 1) {
            Jitems.get("pagination").appendChild(PaginationFrg.get(configs.totalPage, configs.currentPage));
        }
        Jitems.get("footer").classList.remove("d-none");
    }
    static get() {
        configs.lock = true;
        gform
            .request("get", configs.url + "/total")
            .on("progress", gform.progress)
            .send()
            .then((response) => {
            const { errors, data } = response;
            configs.lock = false;
            if (errors) {
                gform.error(errors, configs.context);
                return;
            }
            if (!data || !("total" in data)) {
                return;
            }
            configs.totalItems = Number(data.total);
            Jitems.get("total").textContent = String(configs.totalItems);
            configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);
            this.build();
        });
    }
    static init() {
        PaginationFrg.init();
        Jitems.get("pagination").addEventListener("click", (e) => this.handler(e));
    }
}
class Items {
    static getInputs() {
        const uinputs = {
            filterby: configs.filterBy,
            filterval: configs.filterVal,
            searchby: configs.searchBy,
            searchval: configs.searchVal,
            count: configs.listCount,
            page: configs.currentPage,
            orderby: configs.orderBy,
            sortby: configs.sortBy
        };
        return uinputs;
    }
    static onLoad() {
        const elmsList = ["loading", "noitems", "items", "footer"];
        for (const e of elmsList) {
            Jitems.get(e).classList.add("d-none");
        }
    }
    static onSuccess(content) {
        if (!content) {
            Jitems.get("noitems").classList.remove("d-none");
            return;
        }
        Jitems.get("items").innerHTML = content;
        Jitems.get("items").classList.remove("d-none");
        if (configs.totalPage) {
            Pagination.build();
        }
        else {
            Pagination.get();
        }
    }
    static get() {
        configs.lock = true;
        gform
            .request("get", configs.url)
            .data(this.getInputs())
            .on("progress", gform.progress)
            .send()
            .then((response) => {
            const { errors, data } = response;
            configs.lock = false;
            this.onLoad();
            if (errors) {
                gform.error(errors, configs.context);
                return;
            }
            let content = "";
            if (data && "content" in data && typeof data.content === "string") {
                content = data.content;
            }
            this.onSuccess(content);
        });
    }
}
class Order {
    static filter(elm) {
        const orderbyElm = Jitems.get("orderby"), sortbyElm = Jitems.get("sortby");
        if (configs.orderBy === elm.getAttribute("data-id")) {
            configs.sortBy = 1;
        }
        else {
            configs.sortBy = 0;
        }
        configs.orderBy = elm.getAttribute("data-id") || "";
        configs.currentPage = 1;
        this.clear();
        this.apply();
        orderbyElm.value = configs.orderBy;
        sortbyElm.value = String(configs.sortBy);
        Items.get();
    }
    static handler(e) {
        let elm = e.target;
        if (elm.tagName === "SPAN") {
            elm = elm.parentElement || elm;
        }
        if (configs.lock || !elm.getAttribute("data-id")) {
            return;
        }
        this.filter(elm);
    }
    static apply() {
        const elm = Jitems.get("order").querySelector("[data-id='" + configs.orderBy + "']");
        if (!elm) {
            return;
        }
        let className;
        if (configs.sortBy) {
            className = "fa-sort-amount-down";
        }
        else {
            className = "fa-sort-amount-down-alt";
        }
        elm.children[0].classList.add(className);
    }
    static clear() {
        for (const elm of Array.from(Jitems.get("order").querySelectorAll("span"))) {
            elm.classList.remove("fa-sort-amount-down", "fa-sort-amount-down-alt");
        }
    }
    static init() {
        Jitems.get("order").addEventListener("click", (e) => this.handler(e));
    }
}
class ListCount {
    static handler() {
        const listCountElm = Jitems.get("listcount");
        if (configs.lock) {
            listCountElm.value = String(configs.listCount);
            return;
        }
        configs.listCount = Number(listCountElm.value);
        configs.currentPage = 1;
        configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);
        Items.get();
    }
    static init() {
        Jitems.get("listcount").addEventListener("change", () => this.handler());
    }
}
class ValidateSearch {
    static isValidFilter() {
        const filterbyElms = Jitems.getAll("filterby"), filtervalElms = Jitems.getAll("filterval"), length = filterbyElms.length;
        let isValid = true;
        for (let i = 0; i < length; i++) {
            if (filterbyElms[i].value && !filtervalElms[i].value) {
                filtervalElms[i].classList.add("is-invalid");
                isValid = false;
                break;
            }
        }
        return isValid;
    }
    static isValidOrder() {
        const orderbyElm = Jitems.get("orderby"), sortbyElm = Jitems.get("sortby");
        let isValid = true;
        if (orderbyElm.value && !sortbyElm.value) {
            sortbyElm.classList.add("is-invalid");
            isValid = false;
        }
        else if (sortbyElm.value && !orderbyElm.value) {
            orderbyElm.classList.add("is-invalid");
            isValid = false;
        }
        return isValid;
    }
    static isValidSearch() {
        const searchbyElms = Jitems.getAll("searchby"), searchvalElms = Jitems.getAll("searchval"), length = searchbyElms.length;
        let isValid = true;
        for (let i = 0; i < length; i++) {
            if (searchbyElms[i].value && !searchvalElms[i].value) {
                searchvalElms[i].classList.add("is-invalid");
                isValid = false;
                break;
            }
            else if (searchvalElms[i].value && !searchbyElms[i].value) {
                searchbyElms[i].classList.add("is-invalid");
                isValid = false;
                break;
            }
        }
        return isValid;
    }
    static reset() {
        const elmsList = Jitems.get("ufilters").querySelectorAll(".is-invalid");
        for (const elm of Array.from(elmsList)) {
            elm.classList.remove("is-invalid");
        }
    }
}
class Search {
    static filter(elm) {
        const orderbyElm = Jitems.get("orderby"), sortbyElm = Jitems.get("sortby");
        ValidateSearch.reset();
        if (elm.getAttribute("data-action") === "reset") {
            this.reset();
        }
        configs.filterBy = this.getValues(Jitems.getAll("filterby"));
        configs.filterVal = this.getValues(Jitems.getAll("filterval"));
        configs.searchBy = this.getValues(Jitems.getAll("searchby"));
        configs.searchVal = this.getValues(Jitems.getAll("searchval"));
        configs.orderBy = orderbyElm.value;
        configs.sortBy = Number(sortbyElm.value);
        configs.currentPage = 1;
        configs.totalPage = 0;
        Order.clear();
        if (configs.orderBy) {
            Order.apply();
        }
        Items.get();
    }
    static getValues(elms) {
        const vals = [];
        for (const elm of elms) {
            vals.push(elm.value);
        }
        return vals;
    }
    static handler(e) {
        const elm = e.target;
        if (configs.lock || elm.tagName !== "BUTTON") {
            return;
        }
        switch (elm.getAttribute("data-action")) {
            case "search":
                if (this.isValid()) {
                    this.filter(elm);
                }
                break;
            case "reset":
                this.filter(elm);
                break;
        }
    }
    static isValid() {
        let valid = true;
        ValidateSearch.reset();
        if (!ValidateSearch.isValidFilter() ||
            !ValidateSearch.isValidSearch() ||
            !ValidateSearch.isValidOrder()) {
            valid = false;
        }
        return valid;
    }
    static reset() {
        const elmsList = [
            ...Jitems.getAll("filterby"),
            ...Jitems.getAll("searchby"),
            ...Jitems.getAll("searchval"),
            Jitems.get("orderby"),
            Jitems.get("sortby")
        ], filtervalElms = Jitems.getAll("filterval");
        for (const elm of elmsList) {
            elm.value = "";
        }
        for (const elm of filtervalElms) {
            for (const e of Array.from(elm.children)) {
                e.classList.add("d-none");
            }
            elm.children[0].classList.remove("d-none");
            elm.value = "";
        }
    }
    static init() {
        Jitems.get("ufilters").addEventListener("click", (e) => this.handler(e));
    }
}
class Filter {
    static handler(e) {
        const elm = e.target, index = Jitems.getAll("filterby").indexOf(elm), filterbyElm = Jitems.getAll("filterby")[index], filtervalElm = Jitems.getAll("filterval")[index];
        for (const e of Array.from(filtervalElm.children)) {
            e.classList.add("d-none");
        }
        if (filterbyElm.value) {
            const elmsList = filtervalElm.querySelectorAll("[data-item='" + filterbyElm.value + "']");
            for (const e of Array.from(elmsList)) {
                e.classList.remove("d-none");
            }
            elmsList[0].selected = true;
        }
        else {
            filtervalElm.children[0].classList.remove("d-none");
            filtervalElm.value = "";
        }
    }
    static init() {
        for (const elm of Jitems.getAll("filterby")) {
            elm.addEventListener("change", (e) => this.handler(e));
        }
    }
}
class Ufilter {
    static apply() {
        if (configs.filterBy.length) {
            this.applyFilter();
        }
        if (configs.searchBy.length) {
            this.applySearch();
        }
        if (configs.filterBy.length || configs.searchBy.length) {
            Jitems.get("ufilters").classList.remove("d-none");
        }
        if (configs.orderBy) {
            this.applyOrder();
        }
        Jitems.get("listcount").value = String(configs.listCount);
    }
    static applyFilter() {
        const filterbyElms = Jitems.getAll("filterby"), filtervalElms = Jitems.getAll("filterval"), l = configs.filterBy.length;
        let elmsList;
        for (let i = 0; i < l; i++) {
            if (!configs.filterBy[i]) {
                continue;
            }
            filterbyElms[i].value = configs.filterBy[i];
            elmsList = filtervalElms[i].querySelectorAll("[data-item='" + configs.filterBy[i] + "']");
            for (const e of Array.from(elmsList)) {
                e.classList.remove("d-none");
            }
            elmsList[0].selected = true;
        }
    }
    static applyOrder() {
        Order.apply();
        Jitems.get("orderby").value = configs.orderBy;
        Jitems.get("sortby").value = String(configs.sortBy);
    }
    static applySearch() {
        const searchbyElms = Jitems.getAll("searchby"), searchvalElms = Jitems.getAll("searchval"), l = configs.searchBy.length;
        for (let i = 0; i < l; i++) {
            if (!configs.searchBy[i]) {
                continue;
            }
            searchbyElms[i].value = configs.searchBy[i];
            searchvalElms[i].value = configs.searchVal[i];
        }
    }
    static init() {
        Jitems.get("filter").addEventListener("click", () => Jitems.get("ufilters").classList.toggle("d-none"));
        this.apply();
    }
}
class Configs {
    static init() {
        configs = {
            context: document.body,
            filterBy: [],
            filterVal: [],
            searchBy: [],
            searchVal: [],
            currentPage: 1,
            listCount: 5,
            orderBy: "",
            sortBy: 0,
            url: "",
            lock: false,
            totalItems: 0,
            totalPage: 0
        };
    }
    static normalize() {
        configs.filterBy = this.toArray(configs.filterBy);
        configs.filterVal = this.toArray(configs.filterVal);
        configs.searchBy = this.toArray(configs.searchBy);
        configs.searchVal = this.toArray(configs.searchVal);
        if (configs.url.substr(-1) === "/") {
            configs.url = configs.url.substr(0, configs.url.length - 1);
        }
    }
    static toArray(obj) {
        const aobj = [];
        if (Array.isArray(obj)) {
            return obj;
        }
        for (const [k, v] of Object.entries(obj)) {
            aobj[Number(k)] = v;
        }
        return aobj;
    }
}
class Application {
    confirm(msg, action) {
        Confirm.add(action);
        Confirm.show(msg);
    }
    init(uconfigs) {
        if (Configs.initialized) {
            return;
        }
        Configs.initialized = true;
        Configs.init();
        if (uconfigs) {
            Object.assign(configs, uconfigs);
        }
        Configs.normalize();
        Jitems.init();
        Confirm.init();
        Status.init();
        Pagination.init();
        Order.init();
        ListCount.init();
        Search.init();
        Filter.init();
        Ufilter.init();
        Items.get();
    }
}
function gapp() {
    return new Application();
}
function init() {
    gform = new GForm();
    window.GApp = gapp;
}
init();

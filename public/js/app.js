(function ($) {
    "use strict";
    var configs, gform;
    var Jitems = (function () {
        function Jitems() {
        }
        Jitems.get = function (key) {
            if (!this.caches[key]) {
                this.caches[key] = $("[data-jitem='" + key + "']", configs.context);
            }
            return this.caches[key];
        };
        Jitems.init = function () {
            this.caches = Object.create(null);
        };
        return Jitems;
    }());
    var Confirm = (function () {
        function Confirm() {
        }
        Confirm.handler = function (e) {
            var elm = e.target;
            var action = "";
            if (elm.tagName === "BUTTON") {
                action = $(elm).attr("data-action") || "";
            }
            switch (action) {
                case "confirm":
                    if (this.action) {
                        this.action.call(undefined);
                    }
                    this.action = null;
                    break;
                case "cancel":
                    this.hide();
                    this.action = null;
                    break;
            }
        };
        Confirm.add = function (action) {
            this.action = action;
        };
        Confirm.hide = function () {
            Jitems.get("confirm").addClass("d-none");
        };
        Confirm.init = function () {
            var _this = this;
            Jitems.get("confirm").on("click", function (e) { return _this.handler(e); });
        };
        Confirm.show = function (msg) {
            if (Jitems.get("confirm-msg").text() !== msg) {
                Jitems.get("confirm-msg").text(msg);
            }
            Jitems.get("confirm").removeClass("d-none");
            var elmPosition = Jitems.get("confirm").offset();
            if (elmPosition) {
                $("html, body").animate({
                    scrollTop: elmPosition.top
                });
            }
        };
        return Confirm;
    }());
    var Status = (function () {
        function Status() {
        }
        Status.change = function (elm) {
            gform.submit({
                context: configs.context,
                data: {
                    "j-ar": "r",
                    action: "changeStatus",
                    id: elm.closest(".g-tr").attr("data-id") || ""
                },
                success: function (rstatus) {
                    var status = !elm.hasClass("text-success");
                    if (!rstatus) {
                        Confirm.hide();
                        return;
                    }
                    if (status) {
                        elm.addClass("fa-check text-success").removeClass("fa-times text-danger");
                    }
                    else {
                        elm.addClass("fa-times text-danger").removeClass("fa-check text-success");
                    }
                    Confirm.hide();
                }
            });
        };
        Status.handler = function (e) {
            var _this = this;
            var elm = e.target;
            if (configs.lock || elm.tagName !== "BUTTON" || $(elm).attr("data-item") !== "status") {
                return;
            }
            Confirm.add(function () { return _this.change($(elm)); });
            Confirm.show("Confirm change status");
        };
        Status.init = function () {
            var _this = this;
            Jitems.get("items").on("click", function (e) { return _this.handler(e); });
        };
        return Status;
    }());
    var PaginationFrg = (function () {
        function PaginationFrg() {
        }
        PaginationFrg.getBtnFrg = function () {
            var btnFrg = $(document.createElement("button"));
            btnFrg.attr("type", "button");
            btnFrg.attr("class", "page-link");
            return btnFrg;
        };
        PaginationFrg.getItemFrg = function (page, isActive) {
            var frg = this.listElm.clone().append(this.btnElm.clone().text(page));
            if (isActive) {
                frg.addClass("active");
            }
            return frg;
        };
        PaginationFrg.getListFrg = function () {
            var listFrg = $(document.createElement("li"));
            listFrg.attr("class", "page-item mb-2");
            return listFrg;
        };
        PaginationFrg.get = function (totalPage, currentPage) {
            var frg = $(document.createDocumentFragment()), sideLinksCount = 2, totalLinks = sideLinksCount * 2;
            var paginationStart = 1, paginationEnd;
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
                frg.append(this.getItemFrg("Start"));
            }
            if (currentPage > 1) {
                frg.append(this.getItemFrg("Previous"));
            }
            paginationStart -= 1;
            while (++paginationStart <= paginationEnd) {
                frg.append(this.getItemFrg(paginationStart, paginationStart === currentPage));
            }
            if (currentPage < totalPage) {
                frg.append(this.getItemFrg("Next"));
            }
            if (totalPage > totalLinks + 1 && currentPage + 1 < totalPage) {
                frg.append(this.getItemFrg("End"));
            }
            return frg;
        };
        PaginationFrg.init = function () {
            this.btnElm = this.getBtnFrg();
            this.listElm = this.getListFrg();
        };
        return PaginationFrg;
    }());
    var Pagination = (function () {
        function Pagination() {
        }
        Pagination.getPage = function (page) {
            var currentPage;
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
        };
        Pagination.handler = function (e) {
            var elm = e.target;
            if (configs.lock || elm.tagName !== "BUTTON") {
                return;
            }
            configs.currentPage = this.getPage($(elm).text());
            Items.get();
        };
        Pagination.build = function () {
            Jitems.get("pagination")
                .children()
                .remove();
            if (configs.totalPage > 1) {
                Jitems.get("pagination").append(PaginationFrg.get(configs.totalPage, configs.currentPage));
            }
            Jitems.get("footer").removeClass("d-none");
        };
        Pagination.get = function () {
            var _this = this;
            gform.submit({
                context: configs.context,
                data: {
                    "j-ar": "r",
                    action: "getTotal"
                },
                success: function (total) {
                    if (!total) {
                        return;
                    }
                    configs.totalItems = Number(total);
                    Jitems.get("total").text(configs.totalItems);
                    configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);
                    _this.build();
                }
            }, true);
        };
        Pagination.init = function () {
            var _this = this;
            PaginationFrg.init();
            Jitems.get("pagination").on("click", function (e) { return _this.handler(e); });
        };
        return Pagination;
    }());
    var Items = (function () {
        function Items() {
        }
        Items.getInputs = function () {
            var uinputs = {
                "j-ar": "r",
                action: "getItems",
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
        };
        Items.onLoad = function () {
            configs.lock = false;
            ["loading", "noitems", "items", "footer"].forEach(function (v) { return Jitems.get(v).addClass("d-none"); });
        };
        Items.onSuccess = function (rdata) {
            if (!rdata) {
                Jitems.get("noitems").removeClass("d-none");
                return;
            }
            Jitems.get("items")
                .html(rdata)
                .removeClass("d-none");
            if (configs.totalPage) {
                Pagination.build();
            }
            else {
                Pagination.get();
            }
        };
        Items.get = function () {
            configs.lock = true;
            gform.submit({
                context: configs.context,
                data: this.getInputs(),
                load: this.onLoad,
                success: this.onSuccess
            }, true);
        };
        return Items;
    }());
    var Order = (function () {
        function Order() {
        }
        Order.filter = function (elm) {
            if (configs.orderBy === elm.attr("data-id")) {
                configs.sortBy = Number(!configs.sortBy);
            }
            else {
                configs.sortBy = 0;
            }
            configs.orderBy = elm.attr("data-id") || "";
            configs.currentPage = 1;
            this.clear();
            this.apply(elm);
            Jitems.get("orderby").val(configs.orderBy);
            Jitems.get("sortby").val(configs.sortBy);
            Items.get();
        };
        Order.handler = function (e) {
            var elm = e.target;
            if (elm.tagName === "SPAN") {
                elm = elm.parentElement || elm;
            }
            if (configs.lock || elm.tagName !== "DIV" || !$(elm).attr("data-id")) {
                return;
            }
            this.filter($(elm));
        };
        Order.apply = function (elm) {
            if (!elm) {
                elm = $("[data-id='" + configs.orderBy + "']", Jitems.get("order"));
            }
            elm.children().addClass("fa-sort-amount-down" + (configs.sortBy ? "" : "-alt"));
        };
        Order.clear = function () {
            $("span", Jitems.get("order")).removeClass("fa-sort-amount-down fa-sort-amount-down-alt");
        };
        Order.init = function () {
            var _this = this;
            Jitems.get("order").on("click", function (e) { return _this.handler(e); });
        };
        return Order;
    }());
    var ListCount = (function () {
        function ListCount() {
        }
        ListCount.handler = function () {
            if (configs.lock) {
                Jitems.get("listcount").val(configs.listCount);
                return;
            }
            configs.listCount = Number(Jitems.get("listcount").val());
            configs.currentPage = 1;
            configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);
            Items.get();
        };
        ListCount.init = function () {
            var _this = this;
            Jitems.get("listcount").on("change", function () { return _this.handler(); });
        };
        return ListCount;
    }());
    var ValidateSearch = (function () {
        function ValidateSearch() {
        }
        ValidateSearch.isValidFilter = function () {
            var isValid = true;
            var items = Jitems.get("filterby"), length = items.length;
            for (var i = 0; i < length; i++) {
                if (items.eq(i).val() &&
                    !Jitems.get("filterval")
                        .eq(i)
                        .val()) {
                    Jitems.get("filterval")
                        .eq(i)
                        .addClass("is-invalid");
                    isValid = false;
                    break;
                }
            }
            return isValid;
        };
        ValidateSearch.isValidOrder = function () {
            var isValid = true;
            if (Jitems.get("orderby").val() && !Jitems.get("sortby").val()) {
                Jitems.get("sortby").addClass("is-invalid");
                isValid = false;
            }
            else if (Jitems.get("sortby").val() && !Jitems.get("orderby").val()) {
                Jitems.get("orderby").addClass("is-invalid");
                isValid = false;
            }
            return isValid;
        };
        ValidateSearch.isValidSearch = function () {
            var isValid = true;
            var items = Jitems.get("searchval"), length = items.length;
            for (var i = 0; i < length; i++) {
                if (items.eq(i).val() &&
                    !Jitems.get("searchby")
                        .eq(i)
                        .val()) {
                    Jitems.get("searchby")
                        .eq(i)
                        .addClass("is-invalid");
                    isValid = false;
                    break;
                }
            }
            return isValid;
        };
        ValidateSearch.reset = function () {
            $(".is-invalid", Jitems.get("ufilters")).removeClass("is-invalid");
        };
        return ValidateSearch;
    }());
    var Search = (function () {
        function Search() {
        }
        Search.filter = function (elm) {
            ValidateSearch.reset();
            if (elm.attr("data-action") === "reset") {
                this.reset();
            }
            configs.filterBy = this.getValues(Jitems.get("filterby"));
            configs.filterVal = this.getValues(Jitems.get("filterval"));
            configs.searchBy = this.getValues(Jitems.get("searchby"));
            configs.searchVal = this.getValues(Jitems.get("searchval"));
            configs.orderBy = String(Jitems.get("orderby").val());
            configs.sortBy = Number(Jitems.get("sortby").val());
            configs.currentPage = 1;
            configs.totalPage = 0;
            Order.clear();
            if (configs.orderBy) {
                Order.apply();
            }
            Items.get();
        };
        Search.getValues = function (elms) {
            var vals = [];
            elms.map(function (_k, v) { return vals.push(String($(v).val())); });
            return vals;
        };
        Search.handler = function (e) {
            var elm = e.target;
            if (configs.lock || elm.tagName !== "BUTTON") {
                return;
            }
            switch ($(elm).attr("data-action")) {
                case "search":
                    if (!this.isValid()) {
                        break;
                    }
                    this.filter($(elm));
                    break;
                case "reset":
                    this.filter($(elm));
                    break;
            }
        };
        Search.isValid = function () {
            var valid = true;
            ValidateSearch.reset();
            if (!ValidateSearch.isValidFilter() ||
                !ValidateSearch.isValidSearch() ||
                !ValidateSearch.isValidOrder()) {
                valid = false;
            }
            return valid;
        };
        Search.reset = function () {
            Jitems.get("filterby").val("");
            Jitems.get("filterby").trigger("change");
            Jitems.get("searchby").val("");
            Jitems.get("searchval").val("");
            Jitems.get("orderby").val("");
            Jitems.get("sortby").val("");
        };
        Search.init = function () {
            var _this = this;
            Jitems.get("ufilters").on("click", function (e) { return _this.handler(e); });
        };
        return Search;
    }());
    var Filter = (function () {
        function Filter() {
        }
        Filter.handler = function (e) {
            var elm = e.target, index = Jitems.get("filterby").index(elm), fbyElm = Jitems.get("filterby").eq(index), fvalElm = Jitems.get("filterval").eq(index);
            fvalElm.children().addClass("d-none");
            if (fbyElm.val()) {
                $("[data-item='" + fbyElm.val() + "']", fvalElm)
                    .removeClass("d-none")
                    .first()
                    .prop("selected", true);
            }
            else {
                fvalElm
                    .children()
                    .first()
                    .removeClass("d-none")
                    .prop("selected", true);
            }
        };
        Filter.init = function () {
            var _this = this;
            Jitems.get("filterby").on("change", function (e) { return _this.handler(e); });
        };
        return Filter;
    }());
    var Ufilter = (function () {
        function Ufilter() {
        }
        Ufilter.apply = function () {
            if (configs.filterBy.length) {
                this.applyFilter();
            }
            if (configs.searchBy.length) {
                this.applySearch();
            }
            if (configs.filterBy.length || configs.searchBy.length) {
                Jitems.get("ufilters").removeClass("d-none");
            }
            if (configs.orderBy) {
                this.applyOrder();
            }
            Jitems.get("listcount").val(configs.listCount);
        };
        Ufilter.applyFilter = function () {
            configs.filterBy.forEach(function (v, k) {
                Jitems.get("filterby")
                    .eq(k)
                    .val(v);
                Jitems.get("filterval")
                    .eq(k)
                    .children()
                    .first()
                    .addClass("d-none")
                    .parent()
                    .find("[data-item='" + v + "']")
                    .removeClass("d-none")
                    .filter("[value='" + configs.filterVal[k] + "']")
                    .prop("selected", true);
            });
        };
        Ufilter.applyOrder = function () {
            Order.apply();
            Jitems.get("orderby").val(configs.orderBy);
            Jitems.get("sortby").val(configs.sortBy);
        };
        Ufilter.applySearch = function () {
            configs.searchBy.forEach(function (v, k) {
                Jitems.get("searchby")
                    .eq(k)
                    .val(v);
                Jitems.get("searchval")
                    .eq(k)
                    .val(configs.searchVal[k]);
            });
        };
        Ufilter.init = function () {
            Jitems.get("filter").on("click", function () { return Jitems.get("ufilters").toggleClass("d-none"); });
            this.apply();
        };
        return Ufilter;
    }());
    var Configs = (function () {
        function Configs() {
        }
        Configs.apply = function (uconfigs) {
            for (var k in uconfigs) {
                if (Object.prototype.hasOwnProperty.call(configs, k)) {
                    configs[k] = uconfigs[k];
                }
            }
        };
        Configs.init = function () {
            configs = {
                context: $(),
                filterBy: [],
                filterVal: [],
                searchBy: [],
                searchVal: [],
                currentPage: 1,
                listCount: 5,
                orderBy: "",
                sortBy: 0,
                lock: false,
                totalItems: 0,
                totalPage: 0
            };
        };
        return Configs;
    }());
    var Application = (function () {
        function Application() {
        }
        Application.prototype.addConfirm = function (action) {
            Confirm.add(action);
        };
        Application.prototype.hideConfirm = function () {
            Confirm.hide();
        };
        Application.prototype.init = function (uconfigs) {
            if (Configs.initialized) {
                return;
            }
            Configs.initialized = true;
            Configs.init();
            if (uconfigs) {
                Configs.apply(uconfigs);
            }
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
        };
        Application.prototype.showConfirm = function (msg) {
            Confirm.show(msg);
        };
        return Application;
    }());
    function gapp() {
        return new Application();
    }
    function init() {
        $ = jQuery;
        gform = GForm();
        window.GApp = gapp;
    }
    init();
})();

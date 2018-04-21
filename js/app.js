(function ($) {
    "use strict";

    var gform, jar, configs,

    // self overriding fnc
    getJitem;

    // utils
    getJitem = function () {
        var jitems = Object.create(null);

        getJitem = function (key) {
            if (!jitems[key]) {
                jitems[key] = $("[data-jitem='" + key + "']", jar);
            }

            return jitems[key];
        };

        return getJitem.apply(undefined, arguments);
    };

    function toNumber(val) {
        return Number(val);
    }

    function forEach(obj, callback) {
        Object.keys(obj).forEach(function (k) {
            callback(obj[k], k, obj);
        });
    }


    // action
    function changeStatus(elm) {
        gform.submit({
            data: {
                "j-af": "r",
                action: "changestatus",
                id: elm.closest("tr").data("id")
            },
            success: function (status) {
                if (!status) {
                    return;
                }

                status = !elm.hasClass("text-success");

                elm[status ? "addClass" : "removeClass"]("glyphicon-ok text-success");
                elm[!status ? "addClass" : "removeClass"]("glyphicon-remove text-danger");
            },
            load: function () {
                getJitem("confirm").addClass("hide");
            }
        });
    }

    function actionHandler() {
        $(".btn", getJitem("confirm")).on("click", function () {
            if ($(this).text() === "Cancel") {
                getJitem("confirm").addClass("hide");
            } else {
                changeStatus(configs.action);
            }

            delete configs.action;
        });
    }

    function statusHandler() {
        getJitem("items").on("click", function (e) {
            if (gform.lock) {
                return;
            }

            var elm = e.target;

            if (elm.tagName === "SPAN" && $(elm).data("item") === "status") {
                configs.action = $(elm);
                getJitem("confirm").removeClass("hide");
                $("html, body").animate({ scrollTop: getJitem("confirm").offset().top });
            }
        });
    }


    // pagination
    function paginationFilter(elm) {
        switch (elm.text()) {
            case "Start": {
                configs.currentPage = 1;
                break;
            }

            case "Previous": {
                configs.currentPage -= 1;
                break;
            }

            case "Next": {
                configs.currentPage += 1;
                break;
            }

            case "End": {
                configs.currentPage = configs.totalPage;
                break;
            }

            default: {
                configs.currentPage = toNumber(elm.text());
            }
        }

        if (configs.currentPage >= 1 && configs.currentPage <= configs.totalPage) {
            getItems();
        }
    }

    function paginationHandler() {
        getJitem("pagination").on("click", function (e) {
            if (gform.lock) {
                return;
            }

            var elm = e.target;

            if (elm.tagName === "SPAN") {
                paginationFilter($(elm));
            }
        });
    }

    function buildPaginationFrg(total, current) {
        var frg = "",
        listFrg = "<li><span class='link'>#</span></li>",
        paginationStart = current - 2,
        paginationEnd;

        if (paginationStart < 1) {
            paginationStart = 1;
        }

        paginationEnd = (paginationStart + 4);

        if (paginationEnd > total) {
            paginationStart -= paginationEnd - total;

            if (paginationStart < 1) {
                paginationStart = 1;
            }

            paginationEnd = (total > 1) ? total : 0;
        }

        if (current > 2) {
            frg += listFrg.replace("#", "Start");
        }

        if (current > 1) {
            frg += listFrg.replace("#", "Previous");
        }

        paginationStart -= 1;

        while (++paginationStart <= paginationEnd) {
            if (paginationStart !== current) {
                frg += listFrg.replace("#", paginationStart);
            } else {
                frg += listFrg.replace("<li>", "<li class='active'>").replace("#", paginationStart);
            }
        }

        if (current < total) {
            frg += listFrg.replace("#", "Next");
        }

        if (total > 5 && (current + 1) < total) {
            frg += listFrg.replace("#", "End");
        }

        return frg;
    }

    function buildPagination() {
        getJitem("pagination").children().remove();

        if (configs.totalPage > 1) {
            getJitem("pagination").html(buildPaginationFrg(configs.totalPage, configs.currentPage));
        }

        getJitem("footer").removeClass("hide");
    }

    function getPagination() {
        gform.lock = false;

        gform.submit({
            data: {
                "j-af": "r",
                action: "gettotal"
            },
            success: function (data) {
                if (!data) {
                    return;
                }

                configs.totalItems = toNumber(data);
                $("span", getJitem("total")).text(configs.totalItems);

                configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);
                buildPagination();
            }
        });
    }


    // item
    function getItems() {
        var uinputs = {
            "j-af":     "r",
            action:     "getitems",
            filterby:   configs.filterBy,
            filterval:  configs.filterVal,
            searchby:   configs.searchBy,
            searchval:  configs.searchVal,
            count:      configs.listCount,
            page:       configs.currentPage,
            orderby:    configs.orderBy,
            sortby:     configs.sortBy
        };

        gform.submit({
            data: uinputs,
            success: function (data) {
                if (!data) {
                    getJitem("noitems").removeClass("hide");
                    return;
                }

                getJitem("items").html(data).removeClass("hide");
                configs.totalPage ? buildPagination() : getPagination();
            },
            load: function () {
                ["loading", "noitems", "items", "footer"].forEach(function (v) {
                    getJitem(v).addClass("hide");
                });
            }
        });
    }


    // filters
    function clearOrder() {
        getJitem("order").children().each(function (k, elm) {
            $(elm).children().removeClass("glyphicon-sort-by-attributes" + (toNumber($(elm).data("sort")) ? "-alt" : ""));
        });
    }

    function applyOrder(elm) {
        elm = elm || $("[data-id='" + configs.orderBy + "']", getJitem("order"));
        elm.data("sort", configs.sortBy).children().addClass("glyphicon-sort-by-attributes" + (configs.sortBy ? "-alt" : ""));
    }

    function orderFilter(elm) {
        configs.sortBy = (configs.orderBy === elm.data("id")) ? toNumber(!configs.sortBy) : 0;
        configs.orderBy = elm.data("id");
        configs.currentPage = 1;

        clearOrder();
        applyOrder(elm);

        getJitem("orderby").val(configs.orderBy);
        getJitem("sortby").val(configs.sortBy);

        getItems();
    }

    function orderHandler() {
        getJitem("order").on("click", function (e) {
            if (gform.lock) {
                return;
            }

            var elm = e.target;

            if (elm.tagName === "TH" && $(elm).hasClass("link")) {
                orderFilter($(elm));
            }
        });
    }

    function listCountHandler() {
        getJitem("listcount").on("change", function () {
            if (gform.lock) {
                return;
            }

            configs.listCount = toNumber(this.value);
            configs.currentPage = 1;
            configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);
            getItems();
        });
    }

    function resetSearch() {
        getJitem("filterby").val("");
        getJitem("filterval").val("");
        getJitem("searchby").val("");
        getJitem("searchval").val("");
        getJitem("orderby").val("");
        getJitem("sortby").val("");
    }

    function isValidSearch() {
        if (getJitem("filterby").val() && !getJitem("filterval").val()) {
            getJitem("filterval").parent().addClass("has-error");
            return false;
        }

        if (getJitem("searchby").val() && !getJitem("searchval").val()) {
            getJitem("searchval").parent().addClass("has-error");
            return false;
        }

        if (getJitem("searchval").val() && !getJitem("searchby").val()) {
            getJitem("searchby").parent().addClass("has-error");
            return false;
        }

        if (getJitem("orderby").val() && !getJitem("sortby").val()) {
            getJitem("sortby").parent().addClass("has-error");
            return false;
        }

        if (getJitem("sortby").val() && !getJitem("orderby").val()) {
            getJitem("orderby").parent().addClass("has-error");
            return false;
        }

        return true;
    }

    function searchFilter(elm) {
        var reset = $(elm).hasClass("btn-danger");

        // reset validation
        getJitem("filterval").parent().removeClass("has-error");
        getJitem("searchby").parent().removeClass("has-error");
        getJitem("sortby").parent().removeClass("has-error");

        if (reset) {
            resetSearch();
        } else if (!isValidSearch()) {
            return;
        }

        configs.filterBy = getJitem("filterby").val();
        configs.filterVal = getJitem("filterval").val();
        configs.searchBy = getJitem("searchby").val();
        configs.searchVal = getJitem("searchval").val();
        configs.orderBy = getJitem("orderby").val();
        configs.sortBy = toNumber(getJitem("sortby").val());
        configs.currentPage = 1;
        configs.totalPage = 0;

        clearOrder();

        if (configs.orderBy) {
            applyOrder();
        }

        getItems();
    }

    function searchHandler() {
        getJitem("filter").on("click", function () {
            getJitem("ufilters").toggleClass("hide");
        });

        $(".btn", getJitem("ufilters")).on("click", function () {
            if (gform.lock) {
                return;
            }

            searchFilter(this);
        });
    }

    function filterHandler() {
        getJitem("filterby").on("change", function () {
            getJitem("filterval").val("");
            $("[data-item]", getJitem("filterval")).addClass("hide");
            $("[data-item='" + getJitem("filterby").val() + "']", getJitem("filterval")).removeClass("hide");
        });
    }


    // init
    function applyFilter() {
        if (configs.filterBy) {
            getJitem("filterby").val(configs.filterBy);

            $("[data-item='" + configs.filterBy + "']", getJitem("filterval")).removeClass("hide").each(function (k ,elm) {
                if (elm.value === configs.filterVal) {
                    elm.selected = true;
                    return false;
                }
            });
        }

        if (configs.searchBy) {
            getJitem("searchby").val(configs.searchBy);
            getJitem("searchval").val(configs.searchVal);
        }

        if (configs.filterBy || configs.searchBy) {
            getJitem("ufilters").removeClass("hide");
        }

        if (configs.orderBy) {
            applyOrder();
            getJitem("orderby").val(configs.orderBy);
            getJitem("sortby").val(configs.sortBy);
        }

        getJitem("listcount").val(configs.listCount);
    }

    function init(uconfigs) {
        $ = jQuery;
        gform = new GForm();
        jar = $("#j-ar");
        configs = {
            filterBy:       "",
            filterVal:      "",
            searchBy:       "",
            searchVal:      "",
            currentPage:    1,
            listCount:      5,
            orderBy:        "",
            sortBy:         0
        };

        if (uconfigs) {
            forEach(uconfigs, function (v, k) {
                if (configs.hasOwnProperty(k)) {
                    configs[k] = v;
                }
            });
        }

        gform.init({
            hideErrors: false
        });

        getItems();
        applyFilter();
        listCountHandler();
        paginationHandler();

        if (!uconfigs || !uconfigs.handlers) {
            return;
        }

        forEach({
            search: function () {
                searchHandler();
                filterHandler();
            },
            status: function () {
                actionHandler();
                statusHandler();
            },
            order: function () {
                orderHandler();
            }
        }, function (v, k) {
            if (uconfigs.handlers[k]) {
                v();
            }
        });
    }

    function Application() {}

    Application.prototype = {
        init: init
    };

    function App() {
        if (this instanceof App) {
            return new Application();
        }

        return new App();
    }

    window.App = App;
}());
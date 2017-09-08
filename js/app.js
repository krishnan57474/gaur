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
    function changeStatus() {
        var elm = configs.action;

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
                changeStatus();
            }
        });
    }

    function statusHandler() {
        var elm;

        getJitem("items").on("click", function (e) {
            if (gform.lock) {
                return;
            }

            elm = e.target;

            if (elm.tagName === "SPAN" && $(elm).hasClass("link")) {
                configs.action = $(elm);
                getJitem("confirm").removeClass("hide");
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
        var elm;

        getJitem("pagination").on("click", function (e) {
            if (gform.lock) {
                return;
            }

            elm = e.target;

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

                configs.totalPage  = Math.ceil(configs.totalItems / configs.listCount);
                buildPagination();
            }
        });
    }


    // item
    function getItems() {
        var uinputs = {
            "j-af":   "r",
            action:   "getitems",
            filter:   1,
            filterby: configs.filterBy,
            keyword:  configs.keyword,
            count:    configs.listCount,
            page:     configs.currentPage,
            orderby:  configs.orderBy,
            sortby:   configs.sortBy
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
        if (!elm) {
            elm = $("[data-id='" + configs.orderBy + "']", getJitem("order"));
        }

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
        var elm;

        getJitem("order").on("click", function (e) {
            if (gform.lock) {
                return;
            }

            elm = e.target;

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
            configs.totalPage  = Math.ceil(configs.totalItems / configs.listCount);
            getItems();
        });
    }

    function searchFilter(elm) {
        var reset = $(elm).hasClass("btn-danger");

        // reset validation
        getJitem("filterby").parent().removeClass("has-error");
        getJitem("sortby").parent().removeClass("has-error");

        if (reset) {
            getJitem("filterby").val("");
            getJitem("keyword").val("");
            getJitem("orderby").val("");
            getJitem("sortby").val("");
        }
        else
        {
            if (getJitem("keyword").val() && !getJitem("filterby").val())
            {
                getJitem("filterby").parent().addClass("has-error");
                return;
            }

            if (getJitem("orderby").val() && !getJitem("sortby").val())
            {
                getJitem("sortby").parent().addClass("has-error");
                return;
            }
        }

        configs.filterBy = getJitem("filterby").val();
        configs.keyword = getJitem("keyword").val();
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


    // init
    function applyFilter() {
        if (configs.filterBy) {
            getJitem("filterby").val(configs.filterBy);
            getJitem("keyword").val(configs.keyword);
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
            filterBy: "",
            keyword: "",
            currentPage: 1,
            listCount: 5,
            orderBy: "",
            sortBy: 0
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
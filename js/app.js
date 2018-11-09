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
                id: elm.closest("tr").attr("data-id")
            },
            success: function (status) {
                if (!status) {
                    return;
                }

                status = !elm.hasClass("text-success");

                elm[status ? "addClass" : "removeClass"]("oi-check text-success");
                elm[!status ? "addClass" : "removeClass"]("oi-x text-danger");
            },
            load: function () {
                getJitem("confirm").addClass("d-none");
            }
        });
    }

    function actionHandler() {
        $(".btn", getJitem("confirm")).on("click", function () {
            if ($(this).attr("data-action") === "cancel") {
                getJitem("confirm").addClass("d-none");
            } else {
                changeStatus(configs.action);
            }

            delete configs.action;
        });
    }

    function statusHandler() {
        getJitem("items").on("click", function (e) {
            if (configs.lock) {
                return;
            }

            var elm = e.target;

            if (elm.tagName === "BUTTON" && $(elm).attr("data-item") === "status") {
                configs.action = $(elm);
                getJitem("confirm").removeClass("d-none");
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
                configs.currentPage = Number(elm.text());
            }
        }

        if (configs.currentPage < 1) {
            configs.currentPage = 1;
        }

        if (configs.currentPage > configs.totalPage) {
            configs.currentPage = configs.totalPage;
        }

        getItems();
    }

    function paginationHandler() {
        getJitem("pagination").on("click", function (e) {
            if (configs.lock) {
                return;
            }

            var elm = e.target;

            if (elm.tagName === "SPAN") {
                paginationFilter($(elm));
            }
        });
    }

    function buildPaginationFrg(totalPage, currentPage) {
        var frg = "",
        listFrg = "<li class='page-item mb-2'><span class='page-link'>#</span></li>",
        sideLinksCount = 2,
        totalLinks = sideLinksCount * 2,
        paginationStart = 1,
        paginationEnd;

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
            frg += listFrg.replace("#", "Start");
        }

        if (currentPage > 1) {
            frg += listFrg.replace("#", "Previous");
        }

        paginationStart -= 1;

        while (++paginationStart <= paginationEnd) {
            if (paginationStart !== currentPage) {
                frg += listFrg.replace("#", paginationStart);
            } else {
                frg += listFrg.replace("#", paginationStart).replace("item", "item active");
            }
        }

        if (currentPage < totalPage) {
            frg += listFrg.replace("#", "Next");
        }

        if (totalPage > (totalLinks + 1) && (currentPage + 1) < totalPage) {
            frg += listFrg.replace("#", "End");
        }

        return frg;
    }

    function buildPagination() {
        getJitem("pagination").children().remove();

        if (configs.totalPage > 1) {
            getJitem("pagination").html(buildPaginationFrg(configs.totalPage, configs.currentPage));
        }

        getJitem("footer").removeClass("d-none");
    }

    function getPagination() {
        gform.submit({
            data: {
                "j-af": "r",
                action: "gettotal"
            },
            success: function (total) {
                if (!total) {
                    return;
                }

                configs.totalItems = Number(total);
                getJitem("total").text(configs.totalItems);

                configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);
                buildPagination();
            }
        }, true);
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

        configs.lock = true;

        gform.submit({
            data: uinputs,
            success: function (rdata) {
                if (!rdata) {
                    getJitem("noitems").removeClass("d-none");
                    return;
                }

                getJitem("items").html(rdata).removeClass("d-none");
                configs.totalPage ? buildPagination() : getPagination();
            },
            load: function () {
                configs.lock = false;

                ["loading", "noitems", "items", "footer"].forEach(function (v) {
                    getJitem(v).addClass("d-none");
                });
            }
        }, true);
    }


    // filters
    function clearOrder() {
        $("span", getJitem("order")).removeClass("oi-sort-ascending oi-sort-descending");
    }

    function applyOrder(elm) {
        elm = elm || $("[data-id='" + configs.orderBy + "']", getJitem("order"));
        elm.children().addClass("oi-sort-" + (configs.sortBy ? "descending" : "ascending"));
    }

    function orderFilter(elm) {
        configs.sortBy = (configs.orderBy === elm.attr("data-id")) ? Number(!configs.sortBy) : 0;
        configs.orderBy = elm.attr("data-id");
        configs.currentPage = 1;

        clearOrder();
        applyOrder(elm);

        getJitem("orderby").val(configs.orderBy);
        getJitem("sortby").val(configs.sortBy);

        getItems();
    }

    function orderHandler() {
        getJitem("order").on("click", function (e) {
            if (configs.lock) {
                return;
            }

            var elm = e.target;

            if (elm.tagName === "SPAN") {
                elm = elm.parentNode;
            }

            if (elm.tagName === "TH" && $(elm).hasClass("link")) {
                orderFilter($(elm));
            }
        });
    }

    function listCountHandler() {
        getJitem("listcount").on("change", function () {
            if (configs.lock) {
                getJitem("listcount").val(configs.listCount);
                return;
            }

            configs.listCount = Number($(this).val());
            configs.currentPage = 1;
            configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);
            getItems();
        });
    }

    function resetSearch() {
        getJitem("filterby").val("");
        getJitem("filterby").trigger("change");
        getJitem("searchby").val("");
        getJitem("searchval").val("");
        getJitem("orderby").val("");
        getJitem("sortby").val("");
    }

    function isValidFilter() {
        var isValid = true;

        getJitem("filterby").each(function (k, elm) {
            if ($(elm).val() && !getJitem("filterval").eq(k).val()) {
                getJitem("filterval").eq(k).addClass("is-invalid");
                isValid = false;
                return false;
            }
        });

        return isValid;
    }

    function isValidSearch() {
        var isValid = true;

        getJitem("searchval").each(function (k, elm) {
            if ($(elm).val() && !getJitem("searchby").eq(k).val()) {
                getJitem("searchby").eq(k).addClass("is-invalid");
                isValid = false;
                return false;
            }
        });

        return isValid;
    }

    function isValidOrder() {
        var isValid = true;

        if (getJitem("orderby").val() && !getJitem("sortby").val()) {
            getJitem("sortby").addClass("is-invalid");
            isValid = false;
        } else if (getJitem("sortby").val() && !getJitem("orderby").val()) {
            getJitem("orderby").addClass("is-invalid");
            isValid = false;
        }

        return isValid;
    }

    function getValues(elms) {
        var val = elms.map(function () {
            return $(this).val();
        }).toArray();

        return val;
    }

    function searchFilter(elm) {
        // reset validation
        getJitem("filterval").removeClass("is-invalid");
        getJitem("searchby").removeClass("is-invalid");
        getJitem("sortby").removeClass("is-invalid");
        getJitem("orderby").removeClass("is-invalid");

        if (elm.attr("data-action") === "reset") {
            resetSearch();
        } else if (!isValidFilter() || !isValidSearch() || !isValidOrder()) {
            return;
        }

        configs.filterBy = getValues(getJitem("filterby"));
        configs.filterVal = getValues(getJitem("filterval"));
        configs.searchBy = getValues(getJitem("searchby"));
        configs.searchVal = getValues(getJitem("searchval"));
        configs.orderBy = getJitem("orderby").val();
        configs.sortBy = Number(getJitem("sortby").val());
        configs.currentPage = 1;
        configs.totalPage = 0;

        clearOrder();

        if (configs.orderBy) {
            applyOrder();
        }

        getItems();
    }

    function searchHandler() {
        $(".btn", getJitem("ufilters")).on("click", function () {
            if (configs.lock) {
                return;
            }

            searchFilter($(this));
        });
    }

    function filterHandler() {
        getJitem("filterby").on("change", function () {
            var index = getJitem("filterby").index(this),
            fbyElm = getJitem("filterby").eq(index),
            fvalElm = getJitem("filterval").eq(index);

            fvalElm.children().addClass("d-none");

            if (fbyElm.val()) {
                $("[data-item='" + fbyElm.val() + "']", fvalElm).removeClass("d-none").first().prop("selected", true);
            } else {
                fvalElm.children().first().removeClass("d-none").prop("selected", true);
            }
        });
    }

    function filterToggleHandler() {
        getJitem("filter").on("click", function () {
            getJitem("ufilters").toggleClass("d-none");
        });
    }


    // init
    function applyFilter() {
        if (configs.filterBy.length) {
            configs.filterBy.forEach(function (filterBy, index) {
                getJitem("filterby").eq(index).val(filterBy);
                getJitem("filterval").eq(index).children().first().addClass("hide").parent().find("[data-item='" + filterBy + "']").removeClass("d-none").filter("[value='" + configs.filterVal[index] + "']").prop("selected", true);
            });
        }

        if (configs.searchBy.length) {
            configs.searchBy.forEach(function (searchBy, index) {
                getJitem("searchby").eq(index).val(searchBy);
                getJitem("searchval").eq(index).val(configs.searchVal[index]);
            });
        }

        if (configs.filterBy.length || configs.searchBy.length) {
            getJitem("ufilters").removeClass("d-none");
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
            filterBy:       [],
            filterVal:      [],
            searchBy:       [],
            searchVal:      [],
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

        getItems();
        applyFilter();
        filterToggleHandler();
        listCountHandler();
        paginationHandler();

        if (!uconfigs || !uconfigs.handlers) {
            return;
        }

        forEach({
            search: function () {
                filterHandler();
                searchHandler();
            },
            order: function () {
                orderHandler();
            },
            status: function () {
                actionHandler();
                statusHandler();
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

    window.GApp = App;
}());
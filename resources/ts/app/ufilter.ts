class Ufilter {
    protected static apply(): void {
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
    }

    protected static applyFilter(): void {
        configs.filterBy.forEach((v, k) => {
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
    }

    protected static applyOrder(): void {
        Order.apply();
        Jitems.get("orderby").val(configs.orderBy);
        Jitems.get("sortby").val(configs.sortBy);
    }

    protected static applySearch(): void {
        configs.searchBy.forEach(function(v, k) {
            Jitems.get("searchby")
                .eq(k)
                .val(v);
            Jitems.get("searchval")
                .eq(k)
                .val(configs.searchVal[k]);
        });
    }

    public static init(): void {
        Jitems.get("filter").on("click", () => Jitems.get("ufilters").toggleClass("d-none"));

        this.apply();
    }
}

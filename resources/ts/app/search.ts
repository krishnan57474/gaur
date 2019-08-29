class Search {
    protected static filter(elm: JQuery<HTMLElement>): void {
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
    }

    protected static getValues(elms: JQuery<HTMLElement>): Array<string> {
        const vals: Array<string> = [];

        elms.map((_k: number, v: HTMLElement) => vals.push(String($(v).val())));

        return vals;
    }

    protected static handler(e: JQuery.ClickEvent): void {
        const elm: HTMLElement = e.target;

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
    }

    protected static isValid(): boolean {
        let valid = true;

        ValidateSearch.reset();

        if (
            !ValidateSearch.isValidFilter() ||
            !ValidateSearch.isValidSearch() ||
            !ValidateSearch.isValidOrder()
        ) {
            valid = false;
        }

        return valid;
    }

    protected static reset(): void {
        Jitems.get("filterby").val("");
        Jitems.get("filterby").trigger("change");
        Jitems.get("searchby").val("");
        Jitems.get("searchval").val("");
        Jitems.get("orderby").val("");
        Jitems.get("sortby").val("");
    }

    public static init(): void {
        Jitems.get("ufilters").on("click", (e: JQuery.ClickEvent) => this.handler(e));
    }
}

class Items {
    protected static getInputs(): Record<string, string | number | Array<string>> {
        const uinputs: Record<string, string | number | Array<string>> = {
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
    }

    protected static onLoad(): void {
        configs.lock = false;

        ["loading", "noitems", "items", "footer"].forEach((v) => Jitems.get(v).addClass("d-none"));
    }

    protected static onSuccess(rdata: string): void {
        if (!rdata) {
            Jitems.get("noitems").removeClass("d-none");
            return;
        }

        Jitems.get("items")
            .html(rdata)
            .removeClass("d-none");

        if (configs.totalPage) {
            Pagination.build();
        } else {
            Pagination.get();
        }
    }

    public static get(): void {
        configs.lock = true;

        gform.submit(
            {
                context: configs.context,
                data: this.getInputs(),
                load: this.onLoad,
                success: this.onSuccess
            },
            true
        );
    }
}

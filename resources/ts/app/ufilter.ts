class Ufilter {
    protected static apply(): void {
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

        Jitems.get<HTMLSelectElement>("listcount").value = String(configs.listCount);
    }

    protected static applyFilter(): void {
        const filterbyElms: Array<HTMLSelectElement> = Jitems.getAll("filterby"),
            filtervalElms: Array<HTMLSelectElement> = Jitems.getAll("filterval"),
            l: number = configs.filterBy.length;

        let elmsList: NodeListOf<HTMLOptionElement>;

        for (let i: number = 0; i < l; i++) {
            if (!configs.filterBy[i]) {
                continue;
            }

            filterbyElms[i].value = configs.filterBy[i];

            elmsList = filtervalElms[i].querySelectorAll(
                "[data-item='" + configs.filterBy[i] + "']"
            );

            for (const e of Array.from(elmsList)) {
                e.classList.remove("d-none");
            }

            elmsList[0].selected = true;
        }
    }

    protected static applyOrder(): void {
        Order.apply();
        Jitems.get<HTMLSelectElement>("orderby").value = configs.orderBy;
        Jitems.get<HTMLSelectElement>("sortby").value = String(configs.sortBy);
    }

    protected static applySearch(): void {
        const searchbyElms: Array<HTMLSelectElement> = Jitems.getAll("searchby"),
            searchvalElms: Array<HTMLSelectElement> = Jitems.getAll("searchval"),
            l: number = configs.searchBy.length;

        for (let i: number = 0; i < l; i++) {
            if (!configs.searchBy[i]) {
                continue;
            }

            searchbyElms[i].value = configs.searchBy[i];
            searchvalElms[i].value = configs.searchVal[i];
        }
    }

    public static init(): void {
        Jitems.get("filter").addEventListener("click", () =>
            Jitems.get("ufilters").classList.toggle("d-none")
        );

        this.apply();
    }
}

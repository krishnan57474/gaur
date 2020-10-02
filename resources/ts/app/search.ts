class Search {
    protected static filter(elm: HTMLElement): void {
        const orderbyElm: HTMLSelectElement = Jitems.get("orderby"),
            sortbyElm: HTMLSelectElement = Jitems.get("sortby");

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

    protected static getValues(elms: Array<HTMLSelectElement>): Array<string> {
        const vals: Array<string> = [];

        for (const elm of elms) {
            vals.push(elm.value);
        }

        return vals;
    }

    protected static handler(e: MouseEvent): void {
        const elm: HTMLElement = e.target as HTMLElement;

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

    protected static isValid(): boolean {
        let valid: boolean = true;

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
        const elmsList: Array<HTMLSelectElement> = [
                ...Jitems.getAll<HTMLSelectElement>("filterby"),
                ...Jitems.getAll<HTMLSelectElement>("searchby"),
                ...Jitems.getAll<HTMLSelectElement>("searchval"),
                Jitems.get("orderby"),
                Jitems.get("sortby")
            ],
            filtervalElms: Array<HTMLSelectElement> = Jitems.getAll("filterval");

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

    public static init(): void {
        Jitems.get("ufilters").addEventListener("click", (e: MouseEvent) => this.handler(e));
    }
}

class Order {
    protected static filter(elm: HTMLElement): void {
        const orderbyElm: HTMLSelectElement = Jitems.get("orderby"),
            sortbyElm: HTMLSelectElement = Jitems.get("sortby");

        if (configs.orderBy === elm.getAttribute("data-id")) {
            configs.sortBy = 1;
        } else {
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

    protected static handler(e: MouseEvent): void {
        let elm: HTMLElement = e.target as HTMLElement;

        if (elm.tagName === "SPAN") {
            elm = elm.parentElement || elm;
        }

        if (configs.lock || !elm.getAttribute("data-id")) {
            return;
        }

        this.filter(elm);
    }

    public static apply(): void {
        const elm: HTMLElement | null = Jitems.get("order").querySelector(
            "[data-id='" + configs.orderBy + "']"
        );

        if (!elm) {
            return;
        }

        let className: string;

        if (configs.sortBy) {
            className = "fa-sort-amount-down";
        } else {
            className = "fa-sort-amount-down-alt";
        }

        elm.children[0].classList.add(className);
    }

    public static clear(): void {
        for (const elm of Array.from(Jitems.get("order").querySelectorAll("span"))) {
            elm.classList.remove("fa-sort-amount-down", "fa-sort-amount-down-alt");
        }
    }

    public static init(): void {
        Jitems.get("order").addEventListener("click", (e: MouseEvent) => this.handler(e));
    }
}

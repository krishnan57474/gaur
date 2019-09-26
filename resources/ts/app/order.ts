class Order {
    protected static filter(elm: JQuery<HTMLElement>): void {
        if (configs.orderBy === elm.attr("data-id")) {
            configs.sortBy = Number(!configs.sortBy);
        } else {
            configs.sortBy = 0;
        }

        configs.orderBy = elm.attr("data-id") || "";
        configs.currentPage = 1;

        this.clear();
        this.apply(elm);

        Jitems.get("orderby").val(configs.orderBy);
        Jitems.get("sortby").val(configs.sortBy);

        Items.get();
    }

    protected static handler(e: JQuery.ClickEvent): void {
        let elm: HTMLElement = e.target;

        if (elm.tagName === "SPAN") {
            elm = elm.parentElement || elm;
        }

        if (configs.lock || elm.tagName !== "DIV" || !$(elm).attr("data-id")) {
            return;
        }

        this.filter($(elm));
    }

    public static apply(elm?: JQuery<HTMLElement>): void {
        if (!elm) {
            elm = $("[data-id='" + configs.orderBy + "']", Jitems.get("order"));
        }

        elm.children().addClass("oi-sort-" + (configs.sortBy ? "descending" : "ascending"));
    }

    public static clear(): void {
        $("span", Jitems.get("order")).removeClass("oi-sort-ascending oi-sort-descending");
    }

    public static init(): void {
        Jitems.get("order").on("click", (e: JQuery.ClickEvent) => this.handler(e));
    }
}

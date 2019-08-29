class Filter {
    protected static handler(e: JQuery.ChangeEvent): void {
        const elm: HTMLElement = e.target,
            index = Jitems.get("filterby").index(elm),
            fbyElm = Jitems.get("filterby").eq(index),
            fvalElm = Jitems.get("filterval").eq(index);

        fvalElm.children().addClass("d-none");

        if (fbyElm.val()) {
            $("[data-item='" + fbyElm.val() + "']", fvalElm)
                .removeClass("d-none")
                .first()
                .prop("selected", true);
        } else {
            fvalElm
                .children()
                .first()
                .removeClass("d-none")
                .prop("selected", true);
        }
    }

    public static init(): void {
        Jitems.get("filterby").on("change", (e: JQuery.ChangeEvent) => this.handler(e));
    }
}

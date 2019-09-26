class Status {
    protected static change(elm: JQuery<HTMLElement>): void {
        gform.submit({
            context: configs.context,
            data: {
                "j-ar": "r",
                action: "changeStatus",
                id: elm.closest(".g-tr").attr("data-id") || ""
            },
            success: (rstatus) => {
                const status: boolean = !elm.hasClass("text-success");

                if (!rstatus) {
                    Confirm.hide();
                    return;
                }

                if (status) {
                    elm.addClass("oi-check text-success").removeClass("oi-x text-danger");
                } else {
                    elm.addClass("oi-x text-danger").removeClass("oi-check text-success");
                }

                Confirm.hide();
            }
        });
    }

    protected static handler(e: JQuery.ClickEvent): void {
        const elm: HTMLElement = e.target;

        if (configs.lock || elm.tagName !== "BUTTON" || $(elm).attr("data-item") !== "status") {
            return;
        }

        Confirm.add(() => this.change($(elm)));
        Confirm.show("Confirm change status");
    }

    public static init(): void {
        Jitems.get("items").on("click", (e: JQuery.ClickEvent) => this.handler(e));
    }
}

class Confirm {
    protected static action: VoidFunction | null;

    protected static handler(e: JQuery.ClickEvent): void {
        const elm: HTMLElement = e.target;
        let action: string = "";

        if (elm.tagName === "BUTTON") {
            action = $(elm).attr("data-action") || "";
        }

        switch (action) {
            case "confirm":
                if (this.action) {
                    this.action.call(undefined);
                }

                this.action = null;
                break;

            case "cancel":
                this.hide();
                this.action = null;
                break;
        }
    }

    public static add(action: VoidFunction): void {
        this.action = action;
    }

    public static hide(): void {
        Jitems.get("confirm").addClass("d-none");
    }

    public static init(): void {
        Jitems.get("confirm").on("click", (e: JQuery.ClickEvent) => this.handler(e));
    }

    public static show(msg: string): void {
        if (Jitems.get("confirm-msg").text() !== msg) {
            Jitems.get("confirm-msg").text(msg);
        }

        Jitems.get("confirm").removeClass("d-none");

        const elmPosition: JQuery.Coordinates | undefined = Jitems.get("confirm").offset();

        if (elmPosition) {
            $("html, body").animate({
                scrollTop: elmPosition.top
            });
        }
    }
}

class Confirm {
    protected static callback: VoidFunction | null;

    protected static handler(e: MouseEvent): void {
        const elm: HTMLElement = e.target as HTMLElement;
        let action: string = "",
            callback: VoidFunction | null;

        if (elm.tagName === "BUTTON") {
            action = elm.getAttribute("data-action") || "";
        }

        switch (action) {
            case "confirm":
                callback = this.callback;
                this.hide();
                this.callback = null;

                if (callback) {
                    callback();
                }

                break;

            case "cancel":
                this.hide();
                this.callback = null;
                break;
        }
    }

    protected static hide(): void {
        Jitems.get("confirm").classList.add("d-none");
    }

    public static add(callback: VoidFunction): void {
        this.callback = callback;
    }

    public static init(): void {
        Jitems.get("confirm").addEventListener("click", (e: MouseEvent) => this.handler(e));
    }

    public static show(msg: string): void {
        if (Jitems.get("confirm-msg").textContent !== msg) {
            Jitems.get("confirm-msg").textContent = msg;
        }

        Jitems.get("confirm").classList.remove("d-none");

        Jitems.get("confirm").scrollIntoView({
            behavior: "smooth",
            block: "end"
        });
    }
}

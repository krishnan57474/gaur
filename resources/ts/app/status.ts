class Status {
    protected static change(elm: HTMLElement): void {
        const rowElm: HTMLElement = elm.closest(".g-tr") as HTMLElement,
            id: string = rowElm.getAttribute("data-id") || "0";

        configs.lock = true;

        gform
            .request("post", configs.url + "/" + id + "/status", true)
            .on("progress", gform.progress)
            .send()
            .then((response) => {
                const {errors} = response;

                configs.lock = false;

                if (errors) {
                    gform.error(errors, configs.context);
                    return;
                }

                this.toggleStatus(elm);
            });
    }

    protected static handler(e: MouseEvent): void {
        const elm: HTMLElement = e.target as HTMLElement;

        if (
            configs.lock ||
            elm.tagName !== "BUTTON" ||
            elm.getAttribute("data-item") !== "status"
        ) {
            return;
        }

        Confirm.add(() => this.change(elm));
        Confirm.show("Confirm change status");
    }

    protected static toggleStatus(elm: HTMLElement): void {
        const status: boolean = !elm.classList.contains("text-success");

        if (status) {
            elm.classList.add("fa-check", "text-success");
            elm.classList.remove("fa-times", "text-danger");
        } else {
            elm.classList.add("fa-times", "text-danger");
            elm.classList.remove("fa-check", "text-success");
        }
    }

    public static init(): void {
        Jitems.get("items").addEventListener("click", (e: MouseEvent) => this.handler(e));
    }
}

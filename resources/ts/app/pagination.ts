class Pagination {
    protected static getPage(page: string): number {
        let currentPage: number;

        switch (page) {
            case "Start":
                currentPage = 1;
                break;

            case "Previous":
                currentPage = configs.currentPage - 1;
                break;

            case "Next":
                currentPage = configs.currentPage + 1;
                break;

            case "End":
                currentPage = configs.totalPage;
                break;

            default:
                currentPage = Number(page);
        }

        if (!currentPage || currentPage < 1) {
            currentPage = 1;
        }

        if (currentPage > configs.totalPage) {
            currentPage = configs.totalPage;
        }

        return currentPage;
    }

    protected static handler(e: MouseEvent): void {
        const elm: HTMLElement = e.target as HTMLElement;

        if (configs.lock || elm.tagName !== "BUTTON") {
            return;
        }

        configs.currentPage = this.getPage(elm.textContent || "");
        Items.get();
    }

    public static build(): void {
        for (const elm of Array.from(Jitems.get("pagination").children)) {
            elm.remove();
        }

        if (configs.totalPage > 1) {
            Jitems.get("pagination").appendChild(
                PaginationFrg.get(configs.totalPage, configs.currentPage)
            );
        }

        Jitems.get("footer").classList.remove("d-none");
    }

    public static get(): void {
        configs.lock = true;

        gform
            .request("get", configs.url + "/total", true)
            .data(Items.getInputs())
            .on("progress", gform.progress)
            .send()
            .then((response) => {
                const {errors, data} = response;

                configs.lock = false;

                if (errors) {
                    gform.error(errors, configs.context);
                    return;
                }

                if (!data || !("total" in data)) {
                    return;
                }

                configs.totalItems = Number(data.total);
                Jitems.get("total").textContent = String(configs.totalItems);

                configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);
                this.build();
            });
    }

    public static init(): void {
        PaginationFrg.init();

        Jitems.get("pagination").addEventListener("click", (e: MouseEvent) => this.handler(e));
    }
}

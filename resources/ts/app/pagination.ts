class Pagination {
    protected static getPage(page: string): number {
        let currentPage: number;

        switch (page) {
            case "Start": {
                currentPage = 1;
                break;
            }

            case "Previous": {
                currentPage = configs.currentPage - 1;
                break;
            }

            case "Next": {
                currentPage = configs.currentPage + 1;
                break;
            }

            case "End": {
                currentPage = configs.totalPage;
                break;
            }

            default: {
                currentPage = Number(page);
            }
        }

        if (!currentPage || currentPage < 1) {
            currentPage = 1;
        }

        if (currentPage > configs.totalPage) {
            currentPage = configs.totalPage;
        }

        return currentPage;
    }

    protected static handler(e: JQuery.ClickEvent): void {
        const elm: HTMLElement = e.target;

        if (configs.lock || elm.tagName !== "BUTTON") {
            return;
        }

        configs.currentPage = this.getPage($(elm).text());
        Items.get();
    }

    public static build(): void {
        Jitems.get("pagination")
            .children()
            .remove();

        if (configs.totalPage > 1) {
            Jitems.get("pagination").append(
                PaginationFrg.get(configs.totalPage, configs.currentPage)
            );
        }

        Jitems.get("footer").removeClass("d-none");
    }

    public static get(): void {
        gform.submit(
            {
                context: configs.context,
                data: {
                    "j-ar": "r",
                    action: "getTotal"
                },
                success: (total: string): void => {
                    if (!total) {
                        return;
                    }

                    configs.totalItems = Number(total);
                    Jitems.get("total").text(configs.totalItems);

                    configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);
                    this.build();
                }
            },
            true
        );
    }

    public static init(): void {
        PaginationFrg.init();

        Jitems.get("pagination").on("click", (e: JQuery.ClickEvent) => this.handler(e));
    }
}

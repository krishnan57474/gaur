class Items {
    protected static getInputs(): Record<string, number | string | Array<string>> {
        const uinputs: Record<string, number | string | Array<string>> = {
            filterby: configs.filterBy,
            filterval: configs.filterVal,
            searchby: configs.searchBy,
            searchval: configs.searchVal,
            count: configs.listCount,
            page: configs.currentPage,
            orderby: configs.orderBy,
            sortby: configs.sortBy
        };

        return uinputs;
    }

    protected static onLoad(): void {
        const elmsList: Array<string> = ["loading", "noitems", "items", "footer"];

        for (const e of elmsList) {
            Jitems.get(e).classList.add("d-none");
        }
    }

    protected static onSuccess(content: string): void {
        if (!content) {
            Jitems.get("noitems").classList.remove("d-none");
            return;
        }

        Jitems.get("items").innerHTML = content;
        Jitems.get("items").classList.remove("d-none");

        if (configs.totalPage) {
            Pagination.build();
        } else {
            Pagination.get();
        }
    }

    public static get(): void {
        configs.lock = true;

        gform
            .request("get", configs.url)
            .data(this.getInputs())
            .on("progress", gform.progress)
            .send()
            .then((response) => {
                const {errors, data} = response;

                configs.lock = false;
                this.onLoad();

                if (errors) {
                    gform.error(errors, configs.context);
                    return;
                }

                let content: string = "";

                if (data && "content" in data && typeof data.content === "string") {
                    content = data.content;
                }

                this.onSuccess(content);
            });
    }
}

class PaginationFrg {
    protected static btnElm: JQuery<HTMLButtonElement>;
    protected static listElm: JQuery<HTMLLIElement>;

    protected static getBtnFrg(): JQuery<HTMLButtonElement> {
        const btnFrg: JQuery<HTMLButtonElement> = $(document.createElement("button"));
        btnFrg.attr("type", "button");
        btnFrg.attr("class", "page-link");

        return btnFrg;
    }

    protected static getItemFrg(page: string | number, isActive?: boolean): JQuery<HTMLLIElement> {
        const frg = this.listElm.clone().append(this.btnElm.clone().text(page));

        if (isActive) {
            frg.addClass("active");
        }

        return frg;
    }

    protected static getListFrg(): JQuery<HTMLLIElement> {
        const listFrg: JQuery<HTMLLIElement> = $(document.createElement("li"));
        listFrg.attr("class", "page-item mb-2");

        return listFrg;
    }

    public static get(totalPage: number, currentPage: number): JQuery<DocumentFragment> {
        const frg: JQuery<DocumentFragment> = $(document.createDocumentFragment()),
            sideLinksCount: number = 2,
            totalLinks: number = sideLinksCount * 2;

        let paginationStart: number = 1,
            paginationEnd: number;

        if (currentPage > sideLinksCount) {
            paginationStart = currentPage - sideLinksCount;
        }

        paginationEnd = paginationStart + totalLinks;

        if (paginationEnd > totalPage) {
            paginationStart -= paginationEnd - totalPage;

            if (paginationStart < 1) {
                paginationStart = 1;
            }

            paginationEnd = totalPage;
        }

        if (currentPage > 2) {
            frg.append(this.getItemFrg("Start"));
        }

        if (currentPage > 1) {
            frg.append(this.getItemFrg("Previous"));
        }

        paginationStart -= 1;

        while (++paginationStart <= paginationEnd) {
            frg.append(this.getItemFrg(paginationStart, paginationStart === currentPage));
        }

        if (currentPage < totalPage) {
            frg.append(this.getItemFrg("Next"));
        }

        if (totalPage > totalLinks + 1 && currentPage + 1 < totalPage) {
            frg.append(this.getItemFrg("End"));
        }

        return frg;
    }

    public static init(): void {
        this.btnElm = this.getBtnFrg();
        this.listElm = this.getListFrg();
    }
}

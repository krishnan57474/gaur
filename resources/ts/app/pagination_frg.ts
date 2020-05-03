class PaginationFrg {
    protected static btnElm: HTMLButtonElement;
    protected static listElm: HTMLLIElement;

    protected static getBtnFrg(): HTMLButtonElement {
        const btnFrg: HTMLButtonElement = document.createElement("button");
        btnFrg.setAttribute("type", "button");
        btnFrg.setAttribute("class", "page-link");

        return btnFrg;
    }

    protected static getItemFrg(page: string | number, isActive: boolean): HTMLLIElement {
        const listFrg: HTMLLIElement = this.listElm.cloneNode(true) as HTMLLIElement,
            btnFrg: HTMLButtonElement = this.btnElm.cloneNode(true) as HTMLButtonElement;

        btnFrg.textContent = String(page);
        listFrg.appendChild(btnFrg);

        if (isActive) {
            listFrg.classList.add("active");
        }

        return listFrg;
    }

    protected static getListFrg(): HTMLLIElement {
        const listFrg: HTMLLIElement = document.createElement("li");
        listFrg.setAttribute("class", "page-item mb-2");

        return listFrg;
    }

    public static get(totalPage: number, currentPage: number): DocumentFragment {
        const frg: DocumentFragment = document.createDocumentFragment(),
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
            frg.appendChild(this.getItemFrg("Start", false));
        }

        if (currentPage > 1) {
            frg.appendChild(this.getItemFrg("Previous", false));
        }

        paginationStart -= 1;

        while (++paginationStart <= paginationEnd) {
            frg.appendChild(this.getItemFrg(paginationStart, paginationStart === currentPage));
        }

        if (currentPage < totalPage) {
            frg.appendChild(this.getItemFrg("Next", false));
        }

        if (totalPage > totalLinks + 1 && currentPage + 1 < totalPage) {
            frg.appendChild(this.getItemFrg("End", false));
        }

        return frg;
    }

    public static init(): void {
        this.btnElm = this.getBtnFrg();
        this.listElm = this.getListFrg();
    }
}

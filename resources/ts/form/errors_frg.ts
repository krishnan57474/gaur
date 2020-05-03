class ErrorsFrg {
    protected static btnElm: HTMLButtonElement;
    protected static listElm: HTMLLIElement;
    protected static transitionDuration: number;

    protected static getBtnFrg(): HTMLButtonElement {
        const btnFrg: HTMLButtonElement = document.createElement("button");
        btnFrg.setAttribute("type", "button");
        btnFrg.setAttribute("class", "close");
        btnFrg.setAttribute("data-ebtn", "");
        btnFrg.textContent = "×";

        return btnFrg;
    }

    protected static getListFrg(): HTMLLIElement {
        const listFrg: HTMLLIElement = document.createElement("li");
        listFrg.setAttribute("class", "alert alert-danger alert-dismissible fade show");

        return listFrg;
    }

    public static get(errors: Array<string>, isAutoHide: boolean): DocumentFragment {
        const errorsFrg: DocumentFragment = document.createDocumentFragment();

        for (const e of errors) {
            const frgClone: HTMLLIElement = this.listElm.cloneNode(true) as HTMLLIElement;
            frgClone.textContent = e;

            if (isAutoHide) {
                frgClone.appendChild(this.btnElm.cloneNode(true));
            } else {
                frgClone.classList.remove("alert-dismissible");
            }

            errorsFrg.appendChild(frgClone);
        }

        return errorsFrg;
    }

    public static getTransitionDuration(): number {
        return this.transitionDuration;
    }

    public static init(): void {
        this.btnElm = this.getBtnFrg();
        this.listElm = this.getListFrg();
        this.transitionDuration = getTransitionDuration(this.listElm);
    }
}

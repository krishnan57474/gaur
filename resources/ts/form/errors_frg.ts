class ErrorsFrg {
    protected static btnElm: JQuery<HTMLButtonElement>;
    protected static defaultError: string;
    protected static listElm: JQuery<HTMLLIElement>;
    protected static transitionDuration: number;

    protected static getBtnFrg(): JQuery<HTMLButtonElement> {
        const btnFrg: JQuery<HTMLButtonElement> = $(document.createElement("button"));
        btnFrg.attr("type", "button");
        btnFrg.attr("class", "close");
        btnFrg.attr("data-ebtn", "");
        btnFrg.text("×");

        return btnFrg;
    }

    protected static getListFrg(): JQuery<HTMLLIElement> {
        const listFrg: JQuery<HTMLLIElement> = $(document.createElement("li"));
        listFrg.attr("class", "alert alert-danger alert-dismissible fade show");

        return listFrg;
    }

    public static get(
        errors: Array<string> | string,
        isAutoHide: boolean
    ): JQuery<DocumentFragment> {
        const errorsFrg: JQuery<DocumentFragment> = $(document.createDocumentFragment()),
            errorsList: Array<string> = Array.isArray(errors) ? errors : [this.defaultError];

        let frgClone: JQuery<HTMLLIElement>;

        errorsList.forEach((e: string) => {
            frgClone = this.listElm.clone();
            frgClone.text(e);

            if (isAutoHide) {
                frgClone.append(this.btnElm.clone());
            } else {
                frgClone.removeClass("alert-dismissible");
            }

            errorsFrg.append(frgClone);
        });

        return errorsFrg;
    }

    public static getTransitionDuration(): number {
        return this.transitionDuration;
    }

    public static init(): void {
        this.btnElm = this.getBtnFrg();
        this.listElm = this.getListFrg();
        this.transitionDuration = getTransitionDuration(this.listElm);

        this.defaultError = "Invalid request. Please refresh the page or try again later!";
    }
}

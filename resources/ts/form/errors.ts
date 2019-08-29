class Errors {
    protected static errorElms: Array<JQuery<HTMLUListElement>>;
    protected static timers: Array<number>;

    protected static handler(e: JQuery.ClickEvent): void {
        const elm: HTMLElement = e.target;

        if (elm.tagName !== "BUTTON" || !elm.hasAttribute("data-ebtn")) {
            return;
        }

        this.hide($(elm).closest(".j-error"));
    }

    protected static hide(errorElm: JQuery<HTMLElement>): void {
        errorElm.children().removeClass("show");

        setTimeout(() => errorElm.addClass("d-none"), ErrorsFrg.getTransitionDuration());
    }

    protected static setAutoHide(errorElm: JQuery<HTMLUListElement>): void {
        const timer: number = setTimeout(() => {
            if (!errorElm.hasClass("d-none")) {
                this.hide(errorElm);
            }
        }, 10000);

        this.errorElms.push(errorElm);
        this.timers.push(timer);
    }

    public static clear(): void {
        this.timers.splice(0).forEach(clearTimeout);
        this.errorElms.splice(0).forEach((e) => e.addClass("d-none"));
    }

    public static init(): void {
        ErrorsFrg.init();

        this.errorElms = [];
        this.timers = [];

        $(window).on("click", (e: JQuery.ClickEvent) => this.handler(e));
    }

    public static show(errors: Array<string> | string, context: JQuery<HTMLElement>): void {
        const errorElm: JQuery<HTMLUListElement> = $(".j-error", context),
            isAutoHide: boolean = errorElm.attr("data-show-errors") === undefined;

        this.clear();

        errorElm
            .addClass("d-none")
            .children()
            .remove();
        errorElm.append(ErrorsFrg.get(errors, isAutoHide)).removeClass("d-none");

        const errorElmPosition: JQuery.Coordinates | undefined = errorElm.offset();

        if (isAutoHide) {
            this.setAutoHide(errorElm);
        }

        if (errorElmPosition) {
            $("html, body").animate({
                scrollTop: errorElmPosition.top
            });
        }
    }
}

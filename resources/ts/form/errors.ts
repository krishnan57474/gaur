class Errors {
    protected static errorElms: Array<HTMLUListElement>;
    protected static timers: Array<number>;

    protected static create(errors: Array<string>, errorElm: HTMLUListElement): void {
        const isAutoHide: boolean = !errorElm.hasAttribute("data-show-errors");

        errorElm.classList.add("d-none");

        for (const elm of Array.from(errorElm.children)) {
            elm.remove();
        }

        errorElm.appendChild(ErrorsFrg.get(errors, isAutoHide));
        errorElm.classList.remove("d-none");

        if (isAutoHide) {
            this.setAutoHide(errorElm);
        }

        errorElm.scrollIntoView({
            behavior: "smooth",
            block: "end"
        });
    }

    protected static handler(e: MouseEvent): void {
        const elm: HTMLElement = e.target as HTMLElement;

        if (!elm || elm.tagName !== "BUTTON" || !elm.hasAttribute("data-ebtn")) {
            return;
        }

        this.hide(elm.closest(".j-error"));
    }

    protected static hide(errorElm: HTMLElement | null): void {
        if (!errorElm) {
            return;
        }

        for (const elm of Array.from(errorElm.children)) {
            elm.classList.remove("show");
        }

        setTimeout(() => errorElm.classList.add("d-none"), ErrorsFrg.getTransitionDuration());
    }

    protected static setAutoHide(errorElm: HTMLUListElement): void {
        const timer: number = setTimeout(() => {
            if (!errorElm.classList.contains("d-none")) {
                this.hide(errorElm);
            }
        }, 10000);

        this.errorElms.push(errorElm);
        this.timers.push(timer);
    }

    public static clear(): void {
        for (const t of this.timers.splice(0)) {
            clearTimeout(t);
        }

        for (const elm of this.errorElms.splice(0)) {
            elm.classList.add("d-none");
        }
    }

    public static init(): void {
        ErrorsFrg.init();

        this.errorElms = [];
        this.timers = [];

        addEventListener("click", (e: MouseEvent) => this.handler(e));
    }

    public static show(errors: Array<string>, context: HTMLElement): void {
        const errorElm: HTMLUListElement | null = context.querySelector(".j-error");

        this.clear();

        if (errorElm) {
            this.create(errors, errorElm);
        }
    }
}

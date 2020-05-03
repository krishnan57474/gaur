class Filter {
    protected static handler(e: Event): void {
        const elm: HTMLElement = e.target as HTMLElement,
            index: number = Jitems.getAll("filterby").indexOf(elm),
            filterbyElm: HTMLSelectElement = Jitems.getAll<HTMLSelectElement>("filterby")[index],
            filtervalElm: HTMLSelectElement = Jitems.getAll<HTMLSelectElement>("filterval")[index];

        for (const e of Array.from(filtervalElm.children)) {
            e.classList.add("d-none");
        }

        if (filterbyElm.value) {
            const elmsList: NodeListOf<HTMLOptionElement> = filtervalElm.querySelectorAll(
                "[data-item='" + filterbyElm.value + "']"
            );

            for (const e of Array.from(elmsList)) {
                e.classList.remove("d-none");
            }

            elmsList[0].selected = true;
        } else {
            filtervalElm.children[0].classList.remove("d-none");
            filtervalElm.value = "";
        }
    }

    public static init(): void {
        for (const elm of Jitems.getAll("filterby")) {
            elm.addEventListener("change", (e: Event) => this.handler(e));
        }
    }
}

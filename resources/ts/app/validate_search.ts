class ValidateSearch {
    public static isValidFilter(): boolean {
        const filterbyElms: Array<HTMLSelectElement> = Jitems.getAll("filterby"),
            filtervalElms: Array<HTMLSelectElement> = Jitems.getAll("filterval"),
            length: number = filterbyElms.length;

        let isValid: boolean = true;

        for (let i: number = 0; i < length; i++) {
            if (filterbyElms[i].value && !filtervalElms[i].value) {
                filtervalElms[i].classList.add("is-invalid");
                isValid = false;
                break;
            }
        }

        return isValid;
    }

    public static isValidOrder(): boolean {
        const orderbyElm: HTMLSelectElement = Jitems.get("orderby"),
            sortbyElm: HTMLSelectElement = Jitems.get("sortby");

        let isValid: boolean = true;

        if (orderbyElm.value && !sortbyElm.value) {
            sortbyElm.classList.add("is-invalid");
            isValid = false;
        } else if (sortbyElm.value && !orderbyElm.value) {
            orderbyElm.classList.add("is-invalid");
            isValid = false;
        }

        return isValid;
    }

    public static isValidSearch(): boolean {
        const searchbyElms: Array<HTMLSelectElement> = Jitems.getAll("searchby"),
            searchvalElms: Array<HTMLSelectElement> = Jitems.getAll("searchval"),
            length: number = searchbyElms.length;

        let isValid: boolean = true;

        for (let i: number = 0; i < length; i++) {
            if (searchbyElms[i].value && !searchvalElms[i].value) {
                searchvalElms[i].classList.add("is-invalid");
                isValid = false;
                break;
            } else if (searchvalElms[i].value && !searchbyElms[i].value) {
                searchbyElms[i].classList.add("is-invalid");
                isValid = false;
                break;
            }
        }

        return isValid;
    }

    public static reset(): void {
        const elmsList: NodeListOf<HTMLElement> = Jitems.get("ufilters").querySelectorAll(
            ".is-invalid"
        );

        for (const elm of Array.from(elmsList)) {
            elm.classList.remove("is-invalid");
        }
    }
}

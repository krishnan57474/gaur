class ValidateSearch {
    public static isValidFilter(): boolean {
        let isValid: boolean = true;
        const items: JQuery<HTMLElement> = Jitems.get("filterby"),
            length: number = items.length;

        for (let i: number = 0; i < length; i++) {
            if (
                items.eq(i).val() &&
                !Jitems.get("filterval")
                    .eq(i)
                    .val()
            ) {
                Jitems.get("filterval")
                    .eq(i)
                    .addClass("is-invalid");
                isValid = false;
                break;
            }
        }

        return isValid;
    }

    public static isValidOrder(): boolean {
        let isValid: boolean = true;

        if (Jitems.get("orderby").val() && !Jitems.get("sortby").val()) {
            Jitems.get("sortby").addClass("is-invalid");
            isValid = false;
        } else if (Jitems.get("sortby").val() && !Jitems.get("orderby").val()) {
            Jitems.get("orderby").addClass("is-invalid");
            isValid = false;
        }

        return isValid;
    }

    public static isValidSearch(): boolean {
        let isValid: boolean = true;
        const items: JQuery<HTMLElement> = Jitems.get("searchval"),
            length: number = items.length;

        for (let i: number = 0; i < length; i++) {
            if (
                items.eq(i).val() &&
                !Jitems.get("searchby")
                    .eq(i)
                    .val()
            ) {
                Jitems.get("searchby")
                    .eq(i)
                    .addClass("is-invalid");
                isValid = false;
                break;
            }
        }

        return isValid;
    }

    public static reset(): void {
        $(".is-invalid", Jitems.get("ufilters")).removeClass("is-invalid");
    }
}

class ImportCss {
    protected static getFirstChild(): Node {
        if (!document.head) {
            document.documentElement.insertBefore(
                document.createElement("head"),
                document.documentElement.childNodes[0]
            );
        }

        if (!document.head.childNodes.length) {
            document.head.appendChild(document.createTextNode(""));
        }

        return document.head.childNodes[0];
    }

    protected static getFrg(fileSrc: string, integrity: string): HTMLLinkElement {
        const link: HTMLLinkElement = document.createElement("link");

        link.rel = "stylesheet";
        link.href = fileSrc;

        if (integrity) {
            link.integrity = integrity;
            link.crossOrigin = "anonymous";
        }

        return link;
    }

    protected static import(elm: HTMLScriptElement, firstChild: Node): void {
        const fileSrc: string = elm.getAttribute("data-src") || "",
            integrity: string = elm.getAttribute("data-integrity") || "";

        if (ImportCache.exists(fileSrc)) {
            return;
        }

        ImportCache.add(fileSrc);
        document.head.insertBefore(this.getFrg(fileSrc, integrity), firstChild);
    }

    public static init(): void {
        const elmsList: NodeListOf<HTMLScriptElement> = document.querySelectorAll(".j-acss"),
            firstChild: Node = this.getFirstChild(),
            length: number = elmsList.length;

        ImportCache.reset();

        for (let i: number = 0; i < length; i++) {
            this.import(elmsList[i], firstChild);
        }
    }
}

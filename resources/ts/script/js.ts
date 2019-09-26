class ImportJs {
    protected static asyncQueue(): void {
        if (!window._jq) {
            return;
        }

        window._jq.forEach((callback: VoidFunction) => callback());

        window._jq = null;
    }

    protected static getFrg(
        fileSrc: string,
        integrity: string,
        callback?: VoidFunction
    ): HTMLScriptElement {
        const script: HTMLScriptElement = document.createElement("script");

        script.async = true;
        script.src = fileSrc;

        if (integrity) {
            script.integrity = integrity;
            script.crossOrigin = "anonymous";
        }

        if (callback) {
            script.onload = callback;
        }

        return script;
    }

    protected static getScripts(): Array<HTMLScriptElement> {
        const elmsList: NodeListOf<HTMLScriptElement> = document.querySelectorAll(".j-ajs"),
            length: number = elmsList.length,
            orderedScripts: Array<Array<HTMLScriptElement>> = [],
            scripts: Array<HTMLScriptElement> = [],
            unorderedScripts: Array<HTMLScriptElement> = [];

        let elm: HTMLScriptElement, order: number;

        for (let i: number = 0; i < length; i++) {
            elm = elmsList[i];
            order = Number(elm.getAttribute("data-order"));

            if (order) {
                order -= 1;

                if (!orderedScripts[order]) {
                    orderedScripts[order] = [];
                }

                orderedScripts[order].push(elm);
            } else {
                unorderedScripts.push(elm);
            }
        }

        orderedScripts.forEach((e: Array<HTMLScriptElement>) => scripts.push(...e));

        scripts.push(...unorderedScripts);
        scripts.reverse();

        return scripts;
    }

    protected static import(elm: HTMLScriptElement, scripts: Array<HTMLScriptElement>): void {
        const fileSrc: string = elm.getAttribute("data-src") || "",
            integrity: string = elm.getAttribute("data-integrity") || "",
            skipQueue = elm.hasAttribute("data-skip-queue"),
            callback: VoidFunction = () => this.processQueue(scripts);

        if (ImportCache.exists(fileSrc)) {
            callback();
            return;
        }

        ImportCache.add(fileSrc);
        document.head.appendChild(
            this.getFrg(fileSrc, integrity, skipQueue ? undefined : callback)
        );

        if (skipQueue) {
            callback();
        }
    }

    protected static processQueue(scripts: Array<HTMLScriptElement>): void {
        const elm: HTMLScriptElement | undefined = scripts.pop();

        if (elm) {
            this.import(elm, scripts);
        } else {
            this.asyncQueue();
        }
    }

    public static init(): void {
        const scripts: Array<HTMLScriptElement> = this.getScripts();

        ImportCache.reset();

        this.processQueue(scripts);
    }
}

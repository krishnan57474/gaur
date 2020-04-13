class ImportJs {
    protected static asyncQueue(): void {
        if (!Array.isArray(window._jq)) {
            return;
        }

        for (const callback of window._jq) {
            callback();
        }

        window._jq = null;
    }

    protected static getFrg(
        fileSrc: string,
        integrity: string,
        type: string,
        callback?: VoidFunction
    ): HTMLScriptElement {
        const script: HTMLScriptElement = document.createElement("script");

        script.async = true;
        script.src = fileSrc;

        if (integrity) {
            script.integrity = integrity;
            script.crossOrigin = "anonymous";
        }

        if (type) {
            script.type = type;
        }

        if (callback) {
            script.onload = callback;
        }

        return script;
    }

    protected static getScripts(): Array<HTMLScriptElement> {
        const elmsList: HTMLCollectionOf<HTMLScriptElement> = document.getElementsByClassName(
                "j-ajs"
            ) as HTMLCollectionOf<HTMLScriptElement>,
            orderedScripts: Array<Array<HTMLScriptElement>> = [],
            scripts: Array<HTMLScriptElement> = [],
            unorderedScripts: Array<HTMLScriptElement> = [];

        for (const elm of Array.from(elmsList)) {
            let order: number = Number(elm.getAttribute("data-order"));

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

        for (const elms of orderedScripts) {
            scripts.push(...elms);
        }

        scripts.push(...unorderedScripts);

        return scripts;
    }

    protected static import(elm: HTMLScriptElement): Promise<void> {
        return new Promise((callback) => {
            const fileSrc: string = elm.getAttribute("data-src") || "",
                integrity: string = elm.getAttribute("data-integrity") || "",
                type: string = elm.getAttribute("data-type") || "",
                skipQueue = elm.hasAttribute("data-skip-queue");

            if (ImportCache.exists(fileSrc)) {
                callback();
                return;
            }

            ImportCache.add(fileSrc);
            document.head.appendChild(
                this.getFrg(fileSrc, integrity, type, skipQueue ? undefined : callback)
            );

            if (skipQueue) {
                callback();
            }
        });
    }

    public static async init(): Promise<void> {
        const scripts: Array<HTMLScriptElement> = this.getScripts();

        ImportCache.reset();

        for (const elm of scripts) {
            await this.import(elm);
        }

        this.asyncQueue();
    }
}

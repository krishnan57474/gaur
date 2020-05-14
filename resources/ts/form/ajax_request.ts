class AjaxRequest {
    protected static lock: boolean;

    protected config: AjaxConfigInterface;
    protected xhr: XMLHttpRequest;

    public constructor(config: AjaxConfigInterface) {
        this.config = config;
        this.xhr = new XMLHttpRequest();
    }

    protected encodeUri(data: AjaxConfigDataType): string {
        const uri: Array<string> = [];
        let type: string;

        for (const [k, item] of Object.entries(data)) {
            type = this.typeOf(item);

            if (type === "Array" || type === "Object") {
                for (const [i, v] of Object.entries(item)) {
                    uri.push(
                        encodeURIComponent(k) + "[" + i + "]" + "=" + encodeURIComponent(String(v))
                    );
                }
            } else {
                uri.push(encodeURIComponent(k) + "=" + encodeURIComponent(String(item)));
            }
        }

        return uri.join("&");
    }

    protected createFormData(data: AjaxConfigDataType): FormData {
        const formData: FormData = new FormData();
        let type: string;

        for (const [k, item] of Object.entries(data)) {
            type = this.typeOf(item);

            if (type === "Array" || type === "Object") {
                for (const [i, v] of Object.entries(item)) {
                    formData.set(
                        k + "[" + i + "]",
                        this.typeOf(v) === "File" ? (v as File) : String(v)
                    );
                }
            } else {
                formData.set(k, type === "File" ? (item as File) : String(item));
            }
        }

        return formData;
    }

    protected get data(): FormData | null | string {
        const {config} = this;
        let data: FormData | null | string = null;

        if (
            config.method === "GET" ||
            config.method === "HEAD" ||
            !Object.keys(config.data).length
        ) {
            Object.defineProperty(this, "data", {
                value: data
            });

            return null;
        }

        if (config.upload) {
            data = this.createFormData(config.data);
        } else {
            data = JSON.stringify(config.data);
        }

        Object.defineProperty(this, "data", {
            value: data
        });

        return data;
    }

    protected getUrl(): string {
        const {config} = this;
        let url: string = config.url;

        if (
            (config.method === "GET" || config.method === "HEAD") &&
            Object.keys(config.data).length
        ) {
            if (url.indexOf("?") > -1) {
                url += "&";
            } else {
                url += "?";
            }

            url += this.encodeUri(config.data);
        }

        return url;
    }

    protected setHeaders(): void {
        const {config, xhr} = this,
            {headers} = config;

        xhr.setRequestHeader("Accept", "application/json");

        if (config.method !== "GET" && config.method !== "HEAD" && typeof this.data === "string") {
            xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
        }

        for (const name of Object.keys(headers)) {
            xhr.setRequestHeader(name, headers[name]);
        }
    }

    protected typeOf(v: any): string {
        let type: string = Object.prototype.toString.call(v);
        type = type.substr(8);

        return type.substr(0, type.length - 1);
    }

    public open(ignoreLock: boolean): Promise<AjaxResponse> {
        const {config, xhr} = this;

        return new Promise((success) => {
            if (!ignoreLock && AjaxRequest.lock) {
                return;
            }

            AjaxRequest.lock = true;

            if (config.events.progress) {
                config.events.progress(true);
            }

            xhr.open(config.method, this.getUrl());
            this.setHeaders();

            xhr.onreadystatechange = (): void => {
                if (xhr.readyState === xhr.DONE) {
                    if (config.events.progress) {
                        config.events.progress(false);
                    }

                    success(new AjaxResponse(xhr));
                    AjaxRequest.lock = false;
                }
            };

            xhr.send(this.data);
        });
    }
}

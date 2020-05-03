class AjaxResponse {
    protected raw: XMLHttpRequest;

    public constructor(xhr: XMLHttpRequest) {
        this.raw = xhr;

        Object.defineProperty(this, "raw", {
            value: xhr
        });
    }

    public get data(): AjaxResponseDataType | null {
        const {response} = this;
        let data: AjaxResponseDataType | null = null;

        if (response && response.data) {
            data = response.data;
        }

        Object.defineProperty(this, "data", {
            value: data
        });

        return data;
    }

    public get errors(): Array<string> | null {
        const status: string = String(this.raw.status)[0];
        let errors: Array<string> | null = null;

        if (this.isJson && status !== "4" && status !== "5") {
            Object.defineProperty(this, "errors", {
                value: errors
            });

            return errors;
        }

        const {response} = this;

        if (response && Array.isArray(response.errors)) {
            errors = response.errors;
        } else {
            errors = [this.raw.statusText];
        }

        Object.defineProperty(this, "errors", {
            value: errors
        });

        return errors;
    }

    public get isJson(): boolean {
        const isJson: boolean =
            (this.header("Content-Type") || "").split(";").indexOf("application/json") > -1;

        Object.defineProperty(this, "isJson", {
            value: isJson
        });

        return isJson;
    }

    public get response(): AjaxResponseInterface | null {
        let result: AjaxResponseInterface | null = null;

        if (this.isJson && this.raw.response) {
            try {
                result = JSON.parse(this.raw.response);
            } catch (e) {
                result = null;
            }
        }

        Object.defineProperty(this, "response", {
            value: result
        });

        return result;
    }

    public header(name: string): null | string {
        return this.raw.getResponseHeader(name);
    }
}

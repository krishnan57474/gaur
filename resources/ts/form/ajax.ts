class Ajax {
    protected config: AjaxConfigInterface;
    protected ignoreLock: boolean;

    public constructor(method: string, url: string, ignoreLock: boolean) {
        this.config = {
            data: {},
            events: {},
            headers: {},
            method: method.toUpperCase(),
            upload: false,
            url: "api/" + url
        };

        this.ignoreLock = ignoreLock;
    }

    public data(data: AjaxConfigDataType): this {
        Object.assign(this.config.data, data);
        return this;
    }

    public headers(headers: Record<string, string>): this {
        Object.assign(this.config.headers, headers);
        return this;
    }

    public on(type: string, listener: Function): this {
        this.config.events[type] = listener;
        return this;
    }

    public upload(status: boolean): this {
        this.config.upload = status;
        return this;
    }

    public send(): Promise<AjaxResponse> {
        Errors.clear();
        return new AjaxRequest(this.config).open(this.ignoreLock);
    }
}

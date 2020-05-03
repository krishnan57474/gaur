interface AjaxResponse {
    data: AjaxResponseDataType | null;
    errors: Array<string> | null;
    isJson: boolean;
    response: AjaxResponseInterface | null;
    header(name: string): null | string;
}

interface Ajax {
    data(data: AjaxConfigDataType): this;
    headers(headers: Record<string, string>): this;
    on(type: string, listener: Function): this;
    send(): Promise<AjaxResponse>;
    upload(status: boolean): this;
}

declare class GForm {
    error(errors: Array<string> | string, context: HTMLElement): void;
    progress(status: boolean): void;
    request(method: string, url: string, ignoreLock?: boolean): Ajax;
}

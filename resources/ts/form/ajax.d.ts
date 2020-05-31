type AjaxConfigDataType = Record<
    string,
    Array<File | number | string> | File | Record<string, File | number | string> | number | string
>;

type AjaxResponseDataType =
    | Array<Record<string, Array<string> | string>>
    | Record<string, Array<string> | string>;

interface AjaxConfigInterface {
    data: AjaxConfigDataType;
    events: Record<string, (...a: any) => void>;
    headers: Record<string, string>;
    method: string;
    upload: boolean;
    url: string;
}

interface AjaxResponseInterface {
    data?: AjaxResponseDataType;
    errors?: Array<string>;
}

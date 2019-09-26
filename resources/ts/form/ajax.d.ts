interface AjaxHandlersInterface {
    error: (errors: Array<string> | string) => void;
    progress: (status: boolean) => void;
}

interface AjaxResponseInterface {
    data?: string | Array<string>;
    errors?: Array<string>;
    status: boolean;
}

interface AjaxUserConfigsInterface {
    context: JQuery<HTMLElement>;
    data: Record<string, string | number | Array<string>>;
    error?: (errors: Array<string> | string) => void;
    load?: VoidFunction;
    progress?: (status: boolean) => void;
    success: (data: string | Array<string>) => void;
    upload?: boolean;
    url?: string;
}

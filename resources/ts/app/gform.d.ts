interface AjaxUserConfigsInterface {
    context: JQuery<HTMLElement>;
    data: Record<string, string | number | Array<string>>;
    load?: VoidFunction;
    method?: string;
    success: (data: string) => void;
}

interface GForm {
    submit: (uconfigs: AjaxUserConfigsInterface, ignoreLock?: boolean) => void;
}

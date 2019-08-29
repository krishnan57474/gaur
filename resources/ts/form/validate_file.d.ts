interface ValidateFileInterface {
    context: JQuery<HTMLElement>;
    error?: (errors: Array<string> | string) => void;
    file: File;
    size: string;
    types: Array<string>;
}

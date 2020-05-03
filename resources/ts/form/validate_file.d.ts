interface ValidateFileInterface {
    file: File;
    size: string;
    types: Array<string>;
    error?(e: string): void;
}

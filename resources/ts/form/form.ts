class Form {
    public error(errors: Array<string> | string, context: HTMLElement): void {
        Errors.show(Array.isArray(errors) ? errors : [errors], context);
    }

    public isValidFile(args: ValidateFileInterface): boolean {
        return ValidateFile.isValid(args);
    }

    public progress(status: boolean): void {
        if (status) {
            Progress.show();
        } else {
            Progress.hide();
        }
    }

    public request(method: string, url: string, ignoreLock?: boolean): Ajax {
        return new Ajax(method, url, ignoreLock || false);
    }
}

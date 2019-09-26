class Form {
    public error(errors: Array<string> | string, context: JQuery<HTMLElement>): void {
        Errors.show(errors, context || $());
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

    public submit(uconfigs: AjaxUserConfigsInterface, ignoreLock?: boolean): void {
        Ajax.submit(uconfigs, ignoreLock || false);
    }
}

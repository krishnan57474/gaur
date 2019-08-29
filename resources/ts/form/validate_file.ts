class ValidateFile {
    protected static filesizeError: string;
    protected static invalidFileError: string;

    protected static showError(args: ValidateFileInterface, error: string): void {
        if (args.error) {
            args.error([error]);
        } else {
            Errors.show([error], args.context);
        }
    }

    protected static toBytes(unit: string): number {
        const units: string = "bkmgtpezy",
            size: number = parseFloat(unit.replace(/[^\d.]/g, "")),
            uprefix: string = unit.replace(/[\d.]/g, "").toLowerCase();

        let uindex: number = units.indexOf(uprefix[0]);

        if (uindex < 0) {
            uindex = 0;
        }

        return size * Math.pow(1024, uindex);
    }

    public static init(): void {
        this.filesizeError = "The uploaded file exceeds the maximum upload file size limit.";
        this.invalidFileError = "The filetype you are attempting to upload is not allowed.";
    }

    public static isValid(args: ValidateFileInterface): boolean {
        const {file} = args,
            rx: RegExp = new RegExp("^" + args.types.join("$|^") + "$", "i"),
            ext: string | undefined = file.name.split(".").pop();

        if (!rx.test(ext || "") || !file.size) {
            this.showError(args, this.invalidFileError);
            return false;
        }

        if (file.size > this.toBytes(args.size)) {
            this.showError(args, this.filesizeError);
            return false;
        }

        return true;
    }
}

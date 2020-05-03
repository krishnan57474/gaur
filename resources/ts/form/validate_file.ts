class ValidateFile {
    protected static filesizeError: string;
    protected static invalidFileError: string;

    protected static toBytes(unit: string): number {
        const units: string = "bkmgtpezy",
            size: number = parseFloat(unit),
            uprefix: string = unit.replace(/[^a-zA-Z]+/, "").toLowerCase();

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
            ext: string = file.name.split(".").pop() || "";

        let error: string = "";

        if (!rx.test(ext) || !file.size) {
            error = this.invalidFileError;
        } else if (file.size > this.toBytes(args.size)) {
            error = this.filesizeError;
        }

        if (error && args.error) {
            args.error(error);
        }

        return !error;
    }
}

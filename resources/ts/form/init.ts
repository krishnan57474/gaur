function init(): void {
    Progress.init();
    Errors.init();
    ValidateFile.init();

    window.GForm = gform;
}

init();

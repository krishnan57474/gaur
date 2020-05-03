function init(): void {
    Errors.init();
    Progress.init();
    ValidateFile.init();

    window.GForm = gform;
}

init();

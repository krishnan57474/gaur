class Ajax {
    protected static lock: boolean;

    protected static getConfigs(
        uconfigs: AjaxUserConfigsInterface,
        handlers: AjaxHandlersInterface
    ): JQuery.AjaxSettings {
        const configs: JQuery.AjaxSettings = {
            method: "POST",
            url: uconfigs.url || location.href,
            error: () => this.onError(uconfigs, handlers),
            data: uconfigs.data
        };

        if (uconfigs.upload) {
            configs.processData = false;
            configs.contentType = false;
        }

        return configs;
    }

    protected static getHandlers(uconfigs: AjaxUserConfigsInterface): AjaxHandlersInterface {
        const handlers: AjaxHandlersInterface = {
            error: (errors: Array<string> | string) => {
                if (uconfigs.error) {
                    uconfigs.error(errors);
                } else {
                    Errors.show(errors, uconfigs.context || $());
                }
            },
            progress: (status: boolean) => {
                if (uconfigs.progress) {
                    uconfigs.progress(status);
                } else if (status) {
                    Progress.show();
                } else {
                    Progress.hide();
                }
            }
        };

        return handlers;
    }

    protected static onError(
        uconfigs: AjaxUserConfigsInterface,
        handlers: AjaxHandlersInterface
    ): void {
        if (uconfigs.load) {
            uconfigs.load();
        }

        handlers.progress(false);
        handlers.error("");

        this.lock = false;
    }

    protected static onSuccess(
        uconfigs: AjaxUserConfigsInterface,
        handlers: AjaxHandlersInterface,
        response: AjaxResponseInterface,
        status: string
    ): void {
        if (uconfigs.load) {
            uconfigs.load();
        }

        handlers.progress(false);

        if (status === "success" && response.status && !response.errors) {
            uconfigs.success(response.data || "");
        } else {
            handlers.error(response.errors || "");
        }

        this.lock = false;
    }

    public static submit(uconfigs: AjaxUserConfigsInterface, ignoreLock: boolean): void {
        if (this.lock && !ignoreLock) {
            return;
        }

        const handlers: AjaxHandlersInterface = this.getHandlers(uconfigs),
            configs: JQuery.AjaxSettings = this.getConfigs(uconfigs, handlers);

        this.lock = true;
        Errors.clear();
        handlers.progress(true);

        $.ajax(configs).done((response: AjaxResponseInterface, status: string) =>
            this.onSuccess(uconfigs, handlers, response, status)
        );
    }
}

class Application {
    public confirm(msg: string, action: VoidFunction): void {
        Confirm.add(action);
        Confirm.show(msg);
    }

    public init(uconfigs: UserConfigsInterface): void {
        if (Configs.initialized) {
            return;
        }

        gform = new GForm();

        Configs.initialized = true;
        Configs.init();

        if (uconfigs) {
            Object.assign(configs, uconfigs);
        }

        Configs.normalize();

        Jitems.init();
        Confirm.init();
        Status.init();
        Pagination.init();
        Order.init();
        ListCount.init();
        Search.init();
        Filter.init();
        Ufilter.init();

        Items.get();
    }
}

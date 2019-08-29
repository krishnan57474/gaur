class Application {
    public addConfirm(action: VoidFunction): void {
        Confirm.add(action);
    }

    public hideConfirm(): void {
        Confirm.hide();
    }

    public init(uconfigs: UserConfigsInterface): void {
        if (Configs.initialized) {
            return;
        }

        Configs.initialized = true;
        Configs.init();

        if (uconfigs) {
            Configs.apply(uconfigs);
        }

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

    public showConfirm(msg: string): void {
        Confirm.show(msg);
    }
}

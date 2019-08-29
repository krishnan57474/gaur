class Configs {
    public static initialized: boolean;

    public static apply(uconfigs: UserConfigsInterface): void {
        for (const k in uconfigs) {
            if (Object.prototype.hasOwnProperty.call(configs, k)) {
                configs[k] = uconfigs[k];
            }
        }

        configs.lock = false;
        configs.totalItems = 0;
        configs.totalPage = 0;
    }

    public static init(): void {
        configs = {
            context: $(),
            filterBy: [],
            filterVal: [],
            searchBy: [],
            searchVal: [],
            currentPage: 1,
            listCount: 5,
            orderBy: "",
            sortBy: 0,

            lock: false,
            totalItems: 0,
            totalPage: 0
        };
    }
}

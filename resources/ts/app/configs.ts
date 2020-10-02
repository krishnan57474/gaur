class Configs {
    public static initialized: boolean;

    protected static toArray(obj: Array<string> | Record<number, string>): Array<string> {
        const aobj: Array<string> = [];

        if (Array.isArray(obj)) {
            return obj;
        }

        for (const [k, v] of Object.entries(obj)) {
            aobj[Number(k)] = v;
        }

        return aobj;
    }

    public static init(): void {
        configs = {
            context: document.body,
            filterBy: [],
            filterVal: [],
            searchBy: [],
            searchVal: [],
            currentPage: 1,
            listCount: 5,
            orderBy: "",
            sortBy: 0,
            url: "",

            lock: false,
            totalItems: 0,
            totalPage: 0
        };
    }

    public static normalize(): void {
        configs.filterBy = this.toArray(configs.filterBy);
        configs.filterVal = this.toArray(configs.filterVal);
        configs.searchBy = this.toArray(configs.searchBy);
        configs.searchVal = this.toArray(configs.searchVal);

        if (configs.url.substr(-1) === "/") {
            configs.url = configs.url.substr(0, configs.url.length - 1);
        }
    }
}

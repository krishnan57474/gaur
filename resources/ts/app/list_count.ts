class ListCount {
    protected static handler(): void {
        const listCountElm: HTMLSelectElement = Jitems.get("listcount");

        if (configs.lock) {
            listCountElm.value = String(configs.listCount);
            return;
        }

        configs.listCount = Number(listCountElm.value);
        configs.currentPage = 1;
        configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);

        Items.get();
    }

    public static init(): void {
        Jitems.get("listcount").addEventListener("change", () => this.handler());
    }
}

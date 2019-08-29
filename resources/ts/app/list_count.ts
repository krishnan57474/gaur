class ListCount {
    protected static handler(): void {
        if (configs.lock) {
            Jitems.get("listcount").val(configs.listCount);
            return;
        }

        configs.listCount = Number(Jitems.get("listcount").val());
        configs.currentPage = 1;
        configs.totalPage = Math.ceil(configs.totalItems / configs.listCount);

        Items.get();
    }

    public static init(): void {
        Jitems.get("listcount").on("change", () => this.handler());
    }
}

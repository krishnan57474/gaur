class ImportCache {
    protected static caches: Array<string> = [];

    public static add(url: string): void {
        this.caches.push(url);
    }

    public static exists(url: string): boolean {
        return this.caches.indexOf(url) > -1;
    }
}

class Jitems {
    protected static caches: Record<string, JQuery<HTMLElement>>;

    public static get(key: string): JQuery<HTMLElement> {
        if (!this.caches[key]) {
            this.caches[key] = $("[data-jitem='" + key + "']", configs.context);
        }

        return this.caches[key];
    }

    public static init(): void {
        this.caches = Object.create(null);
    }
}

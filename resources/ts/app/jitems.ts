class Jitems {
    protected static caches: Record<string, Array<HTMLElement>>;

    public static get<T extends HTMLElement>(key: string): T {
        if (!this.caches[key]) {
            this.caches[key] = Array.from(
                document.querySelectorAll<T>("[data-jitem='" + key + "']")
            );
        }

        return this.caches[key][0] as T;
    }

    public static getAll<T extends HTMLElement>(key: string): Array<T> {
        if (!this.caches[key]) {
            this.caches[key] = Array.from(
                document.querySelectorAll<T>("[data-jitem='" + key + "']")
            );
        }

        return this.caches[key] as Array<T>;
    }

    public static init(): void {
        this.caches = Object.create(null);
    }
}

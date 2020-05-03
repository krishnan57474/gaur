class Progress {
    protected static progressBarElm: HTMLDivElement;
    protected static progressElm: HTMLDivElement;
    protected static timer: number;
    protected static transitionDuration: number;

    protected static getProgressBarFrg(): HTMLDivElement {
        const progressBarFrg: HTMLDivElement = document.createElement("div");

        progressBarFrg.setAttribute(
            "class",
            "progress-bar progress-bar-striped progress-bar-animated"
        );

        return progressBarFrg;
    }

    protected static getProgressFrg(): HTMLDivElement {
        const progressFrg: HTMLDivElement = document.createElement("div");

        progressFrg.setAttribute("class", "progress fixed-top");

        progressFrg.style.height = "6px";
        progressFrg.style.width = "0";

        return progressFrg;
    }

    public static hide(): void {
        const {progressBarElm, progressElm} = this;

        clearInterval(this.timer);

        progressBarElm.style.width = "100%";

        setTimeout(() => {
            progressBarElm.style.width = "0";

            setTimeout(() => {
                progressElm.style.width = "0";
            }, this.transitionDuration);
        }, 1000);
    }

    public static init(): void {
        this.progressBarElm = this.getProgressBarFrg();
        this.progressElm = this.getProgressFrg();

        this.timer = 0;
        this.transitionDuration = getTransitionDuration(this.progressBarElm);

        this.progressElm.appendChild(this.progressBarElm);
        document.body.appendChild(this.progressElm);
    }

    public static show(): void {
        const {progressBarElm, progressElm} = this;
        let width: number = 5;

        clearInterval(this.timer);

        progressElm.style.width = "100%";
        progressBarElm.style.width = "0";

        this.timer = setInterval(() => {
            width += 5;

            if (width < 100) {
                progressBarElm.style.width = width + "%";
            } else {
                clearInterval(this.timer);
            }
        }, 500);
    }
}

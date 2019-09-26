class Progress {
    protected static progressBarElm: JQuery<HTMLDivElement>;
    protected static progressElm: JQuery<HTMLDivElement>;
    protected static timer: number;
    protected static transitionDuration: number;

    protected static getProgressBarFrg(): JQuery<HTMLDivElement> {
        const progressBarFrg: JQuery<HTMLDivElement> = $(document.createElement("div"));

        progressBarFrg.attr("class", "progress-bar progress-bar-striped progress-bar-animated");

        return progressBarFrg;
    }

    protected static getProgressFrg(): JQuery<HTMLDivElement> {
        const progressFrg: JQuery<HTMLDivElement> = $(document.createElement("div"));

        progressFrg.attr("class", "progress fixed-top");
        progressFrg.css({
            height: "6px",
            width: 0
        });

        return progressFrg;
    }

    public static hide(): void {
        clearInterval(this.timer);

        this.progressBarElm.css({width: "100%"});

        setTimeout(() => {
            this.progressBarElm.css({width: 0});

            setTimeout(() => this.progressElm.css({width: 0}), this.transitionDuration);
        }, 1000);
    }

    public static init(): void {
        this.progressBarElm = this.getProgressBarFrg();
        this.progressElm = this.getProgressFrg();

        this.timer = 0;
        this.transitionDuration = getTransitionDuration(this.progressBarElm);

        this.progressElm.append(this.progressBarElm);
        $(document.body).append(this.progressElm);
    }

    public static show(): void {
        let width: number = 5;

        clearInterval(this.timer);

        this.progressElm.css({width: "100%"});
        this.progressBarElm.css({width: 0});

        this.timer = setInterval(() => {
            width += 5;

            if (width < 100) {
                this.progressBarElm.css({
                    width: width + "%"
                });
            } else {
                clearInterval(this.timer);
            }
        }, 500);
    }
}

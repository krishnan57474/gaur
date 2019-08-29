function getTransitionDuration(elm: JQuery<HTMLElement>): number {
    const duration: Array<string> = elm.css("transition-duration").split(",");

    return parseFloat(duration[0]) * 1000;
}

function getTransitionDuration(elm: HTMLElement): number {
    const duration: Array<string> = getComputedStyle(elm)
        .getPropertyValue("transition-duration")
        .split(",");

    return parseFloat(duration[0] || "0") * 1000;
}

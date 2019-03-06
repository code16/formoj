
export function smoothScroll(x, y) {
    if('scrollBehavior' in document.documentElement.style) {
        window.scrollTo({
            top: y,
            left: x,
            behavior: 'smooth',
        });
    } else {
        window.scrollTo(x, y);
    }
}
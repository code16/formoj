
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

export function isInsideModal(el) {
    if(!el || el === document.body) {
        return false;
    }
    return window.getComputedStyle(el).position === 'fixed' || isInsideModal(el.parentElement);
}

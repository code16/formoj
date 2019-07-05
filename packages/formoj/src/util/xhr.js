
export function getXsrfToken() {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]*)/);
    return match ? decodeURIComponent(match[1]) : null;
}
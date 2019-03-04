
export function getValidationErrors(data) {
    return Object.entries(data.errors).reduce((res, [key, messages]) => ({
        ...res, [key]: messages[0]
    }), {});
}
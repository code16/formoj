

export function createConfig(config) {
    config = config || {};
    return {
        apiBaseUrl: config.apiBaseUrl,
    };
}

export function config() {
    return this.$formoj;
}
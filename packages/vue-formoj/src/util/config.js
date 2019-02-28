

export function createConfig(config) {
    config = config || {};
    return {
        baseApiUrl: config.baseApiUrl,
    };
}

export function config() {
    return this.$formoj;
}
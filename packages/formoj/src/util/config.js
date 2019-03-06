
export const defaultConfig = {
    apiBaseUrl: '/formoj/api',
    scrollOffset: 0,
};

export function createConfig(config) {
    return {
        ...defaultConfig,
        ...config,
    }
}

export function config() {
    return this.$formoj;
}
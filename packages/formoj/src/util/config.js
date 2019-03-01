
export const defaultConfig = {
    apiBaseUrl: '/formoj/api',
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
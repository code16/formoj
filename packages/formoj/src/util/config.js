import { getDefaultLocale } from "./i18n";

export const defaultConfig = {
    apiBaseUrl: '/formoj/api',
    scrollOffset: 0,
    locale: getDefaultLocale(),
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
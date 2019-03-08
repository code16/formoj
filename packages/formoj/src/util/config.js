import { getDefaultLocale, createI18nConfig } from "./i18n";

export const defaultConfig = {
    apiBaseUrl: '/formoj/api',
    scrollOffset: 0,
    locale: getDefaultLocale(),
};

export function createConfig(config) {
    return {
        ...defaultConfig,
        ...config,
        i18n: createI18nConfig(config.i18n),
    }
}

export function config() {
    return this.$formoj;
}
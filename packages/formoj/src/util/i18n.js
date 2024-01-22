import template from 'lodash/template';
import i18n from '../lang';
import { config } from "./config";

export function createI18nConfig(customs = {}) {
    return {
        ...i18n,
        ...Object.entries(customs).reduce((res, [key, lang]) => ({
            ...res,
            [key]: {
                ...i18n[key],
                ...lang,
            }
        }), {}),
    };
}

export function getDefaultLocale() {
    const langAttribute = document.documentElement.getAttribute('lang');
    return langAttribute
        ? langAttribute.split('-')[0]
        : 'en';
}

function interpolate(message, values) {
    return template(message)(values);
}

export function translate(i18n, locale, key, values) {
    const messages = (locale in i18n) ? i18n[locale] : i18n['en'];
    return (key in messages)
        ? interpolate(messages[key], values)
        : null;
}

export function $t(key, values) {
    const conf = config.call(this) || {};
    return translate(conf.i18n, conf.locale, key, values);
}

import template from 'lodash/template';
import * as i18n from '../lang';
import { config } from "./config";

function interpolate(message, values) {
    return template(message)(values);
}

export function translate(key, locale, values) {
    const messages = (locale in i18n) ? i18n[locale] : i18n['en'];
    return (key in messages)
        ? interpolate(messages[key], values)
        : null;
}

export function $t(key, values) {
    const conf = config.call(this) || {};
    return translate(key, conf.locale, values);
}

export function getDefaultLocale() {
    const langAttribute = document.documentElement.getAttribute('lang');
    return langAttribute
        ? langAttribute.split('-')[0]
        : 'en';
}
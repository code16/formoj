import template from 'lodash/template';
import * as i18n from '../lang';

function interpolate(message, values) {
    return template(message)(values);
}

export function $t(key, values) {
    const messages = i18n['fr'];
    return key in messages
        ? interpolate(messages[key], values)
        : null;
}
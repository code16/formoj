import * as i18n from '../lang';

export function $t(key) {
    const messages = i18n['fr'];
    return messages[key] || null;
}
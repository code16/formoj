
/**
 * @typedef {Object} Conditionable
 * @property {Object.<string, Array>} conditionalDisplay
 */

/**
 * @param {Conditionable} conditionable - field or section
 * @param data
 * @returns {boolean}
 */
export function isVisible(conditionable, data) {
    if(!conditionable.conditionalDisplay) {
        return true;
    }

    return Object.entries(conditionable.conditionalDisplay)
        .every(([fieldId, value]) =>
            [].concat(data?.[fieldId] ?? []).some(v => value.includes(v))
        );
}
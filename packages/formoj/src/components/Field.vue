<template>
    <div class="fj-field" :class="classes">
        <template v-if="isFieldset">
            <div class="fj-field__label">{{ label }}</div>
        </template>
        <template v-else-if="!isContentOnly">
            <label class="fj-field__label" :for="id">{{ label }}</label>
        </template>
        <component
            :is="component"
            :id="id"
            :form-id="formId"
            :value="value"
            :name="name"
            :inner="inner"
            v-bind="props"
            @input="handleInput"
            @error="handleError"
            @clear="handleClear"
        />
        <template v-if="hasError">
            <div class="fj-field__error">{{ errorMessage }}</div>
        </template>
        <template v-if="field.helpText || contextualHelpText">
            <div class="fj-field__help">
                <div class="fj-content" v-html="field.helpText"></div>
                <span class="fj-nowrap">{{ contextualHelpText }}</span>
            </div>
        </template>
    </div>
</template>

<script>
    import { $t } from "../util/i18n";
    import { getFieldByType, isFieldset, isContentOnly } from "./fields";

    export default {
        name: 'FjField',

        props: {
            value: {},
            field: Object,
            id: {
                type: String,
                required: true,
            },
            name: {
                type: String,
                required: true,
            },
            formId: Number,
            error: String,
            // when sections are stacked
            inner: Boolean,
        },

        computed: {
            component() {
                return getFieldByType(this.field.type, {
                    isMultiple: this.field.multiple,
                    isRadios: this.field.radios,
                });
            },
            isFieldset() {
                return isFieldset(this.field.type, {
                    isMultiple: this.field.multiple,
                });
            },
            isContentOnly() {
                return isContentOnly(this.field.type);
            },
            props() {
                const { key, ...field } = this.field;
                return {
                    ...field,
                    // send the label only if no label is displayed here
                    label: this.isContentOnly ? this.field.label : undefined,
                    helpText: undefined,
                }
            },
            isRequired() {
                return this.field.required;
            },
            hasError() {
                return !!this.error;
            },
            errorMessage() {
                return this.error;
            },
            classes() {
                return {
                    'fj-field--required': this.isRequired,
                    'fj-field--invalid': this.hasError,
                };
            },
            label() {
                return this.field.label;
            },
            contextualHelpText() {
                const isMultipleSelectWithMax = (
                    this.field.type === 'select'
                    && this.field.multiple
                    && typeof this.field.max === 'number'
                    && this.field.max < this.field.options?.length
                );
                if(isMultipleSelectWithMax) {
                    return this.$t('field.help_text.select_max', { max:this.field.max });
                }
                return null;
            }
        },
        methods: {
            $t,
            handleInput(value) {
                this.$emit('input', value);
            },
            handleError(message) {
                this.$emit('error', message);
            },
            handleClear() {
                this.$emit('clear');
            },
        },
    }
</script>

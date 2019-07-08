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
            v-bind="props"
            @input="handleInput"
            @error="handleError"
        />
        <template v-if="hasError">
            <div class="fj-field__error">{{ errorMessage }}</div>
        </template>
        <div class="fj-field__help">{{ field.helpText }} <span class="fj-nowrap">{{ contextualHelpText }}</span></div>
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
        },

        computed: {
            component() {
                return getFieldByType(this.field.type, {
                    isMultiple: this.field.multiple,
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
                return {
                    ...this.field,
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
        },
    }
</script>
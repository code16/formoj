<template>
    <div class="fj-field" :class="classes">
        <label class="fj-field__label" :for="id">{{ field.label }}</label>
        <component
            :is="component"
            :id="id"
            :value="value"
            :name="name"
            v-bind="props"
            @input="handleInput"
        />
        <template v-if="hasError">
            <div class="fj-field__error">{{ errorMessage }}</div>
        </template>
        <div class="fj-field__help">{{ field.helpText }}</div>
    </div>
</template>

<script>
    import { getFieldByType } from "./fields";

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
            error: String,
        },

        computed: {
            component() {
                return getFieldByType(this.field.type);
            },
            props() {
                return {
                    ...this.field,
                    label: undefined,
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
        },
        methods: {
            handleInput(value) {
                this.$emit('input', value);
            },
        },
    }
</script>
<template>
    <div class="fj-field" :class="{ 'fj-field--required':isRequired }">
        <label class="fj-field__label" :for="id">{{ field.label }}</label>
        <component :is="component" :id="id" :value="value" v-bind="props" @input="handleInput" />
        <div class="fj-field__help">{{ field.helpText }}</div>
    </div>
</template>

<script>
    import {getFieldByType} from "./fields";

    export default {
        name: 'FjField',

        props: {
            value: {},
            field: Object,
            id: String,
        },

        computed: {
            isRequired() {
                return this.field.required;
            },
            component() {
                return getFieldByType(this.field.type);
            },
            props() {
                return {
                    ...this.field,
                    label: undefined,
                    helpText: undefined,
                    name: this.field.id,
                }
            },
        },
        methods: {
            handleInput(value) {
                this.$emit('input', value);
            },
        },
    }
</script>
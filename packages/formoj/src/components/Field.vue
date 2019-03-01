<template>
    <div class="fj-field">
        <label :for="id">{{ field.label }}</label>
        <component :is="component" :id="id" :value="value" v-bind="field" @input="handleInput" />
    </div>
</template>

<script>
    import {getFieldByType} from "./fields";

    export default {
        name: 'FjField',

        props: {
            value: {},
            field: Object,
            formId: {
                type: [Number, String],
                default: '0',
            },
        },

        computed: {
            id() {
                return `formoj-${this.formId}-field-${this.field.id}`;
            },
            component() {
                return getFieldByType(this.field.type);
            },
        },
        methods: {
            handleInput(value) {
                this.$emit('input', value);
            },
        },
    }
</script>
<template>
    <div class="fj-field" :class="{ 'fj-field--required':isRequired }">
        <label class="fj-field__label" :for="id">{{ field.label }}</label>
        <component :is="component" :id="id" :value="value" v-bind="field" @input="handleInput" />
    </div>
</template>

<script>
    import { getFieldByType } from "./fields";

    export default {
        name: 'FjField',

        props: {
            value: {},
            field: Object,
            id: String,
        },

        computed: {
            component() {
                return getFieldByType(this.field.type);
            },
            isRequired() {
                return this.field.required;
            },
        },
        methods: {
            handleInput(value) {
                this.$emit('input', value);
            },
        },
    }
</script>
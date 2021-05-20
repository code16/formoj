<template>
    <select class="fj-select" v-model="selected" @change="handleChanged">
        <option value="">{{ $t('field.select.placeholder') }}</option>
        <template v-for="option in options">
            <option :value="option.id" :key="option.id">{{ option.label }}</option>
        </template>
    </select>
</template>

<script>
    import { $t } from "../../../util/i18n";

    export default {
        name: 'FjSelect',

        props: {
            value: [String, Number],
            options: Array,
        },

        data() {
            return {
                selected: null,
            }
        },

        watch: {
            value: {
                immediate: true,
                handler: 'handleValueChanged',
            },
        },

        methods: {
            $t,
            handleChanged(e) {
                this.$emit('input', e.target.value);
            },
            handleValueChanged() {
                this.selected = this.value ?? '';
            },
        },
    }
</script>

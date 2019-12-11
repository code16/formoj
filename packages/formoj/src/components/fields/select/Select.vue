<template>
    <select class="fj-select" :value="value" @change="handleChanged">
        <option value="" selected>{{ $t('field.select.placeholder') }}</option>
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
            value: String,
            options: Array,
        },

        methods: {
            $t,
            handleChanged(e) {
                this.$emit('input', e.target.value);
            },
        },
    }
</script>
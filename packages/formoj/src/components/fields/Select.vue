<template>
    <div class="fj-select">
        <template v-if="isMultiple">

        </template>
        <template v-else>
            <select class="fj-select__input" :value="value" v-bind="$attrs" @change="handleChanged">
                <option value="" selected>{{ $t('field.select.placeholder') }}</option>
                <template v-for="option in options">
                    <option :value="option.id" :key="option.id">{{ option.label }}</option>
                </template>
            </select>
        </template>
    </div>
</template>

<script>
    import { $t } from "../../util/i18n";

    export default {
        name: 'FjSelect',

        props: {
            value: String,
            type: String,
            multiple: Boolean,
            options: Array,
        },

        computed: {
            isMultiple() {
                return this.multiple;
            },
        },

        methods: {
            $t,
            handleChanged(e) {
                this.$emit('input', e.target.value);
            },
        },
    }
</script>
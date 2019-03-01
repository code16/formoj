<template>
    <div class="fj-select">
        <template v-if="isMultiple">

        </template>
        <template v-else>
            <select class="fj-select__input" v-bind="$attrs" @change="handleChanged">
                <template v-for="option in options">
                    <option :value="option.id" :key="option.id">{{ option.label }}</option>
                </template>
            </select>
        </template>
    </div>
</template>

<script>
    export default {
        name: 'FjSelect',

        props: {
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
            handleChanged(e) {
                this.$emit('input', e.target.value);
            }
        },
    }
</script>
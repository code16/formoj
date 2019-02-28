<template>
    <input class="fj-text" type="text" :value="value" @input="handleInput">
</template>

<script>
    export default {
        name: 'FjText',

        props: {
            value: String,
        },

        methods: {
            handleInput(e) {
                this.$emit('input', e.target.value);
            },
        },
    }
</script>
<template>
    <textarea class="fj-textarea" @input="handleInput">{{ value }}</textarea>
</template>

<script>
    export default {
        name: 'FjTextarea',

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
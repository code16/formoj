<template>
    <div class="fj-heading">
        <h5 class="fj-heading__title">{{ text }}</h5>
    </div>
</template>

<script>
    export default {
        name: 'FjHeading',

        props: {
            content: String,
        },

        computed: {
            text() {
                return this.content;
            }
        },
    }
</script>
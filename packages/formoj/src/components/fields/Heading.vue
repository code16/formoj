<template>
    <div class="fj-heading" :class="classes">
        <h5 class="fj-heading__title">{{ text }}</h5>
    </div>
</template>

<script>
    export default {
        name: 'FjHeading',

        props: {
            content: String,
            inner: Boolean,
        },

        computed: {
            classes() {
                return {
                    'fj-heading--inner': this.inner,
                }
            },
            text() {
                return this.content;
            }
        },
    }
</script>

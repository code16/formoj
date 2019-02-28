<template>
    <div class="fj-alert" :class="classes">
        <slot />
    </div>
</template>

<script>
    export default {
        name: 'FjAlert',

        props: {
            type: String,
        },

        computed: {
            classes() {
                return {
                    [`fj-alert--${this.type}`]: this.type,
                }
            }
        },
    }
</script>
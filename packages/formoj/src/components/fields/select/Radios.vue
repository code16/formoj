<template>
    <div class="fj-radios">
        <template v-for="option in options">
            <div class="fj-radios__check">
                <input type="radio"
                    :id="radioId(option)"
                    class="fj-radios__check-input"
                    :name="name"
                    :checked="isChecked(option)"
                    @change="handleChanged(option, $event)"
                >
                <label :for="radioId(option)" class="fj-radios__check-label">{{ option.label }}</label>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        name: 'FjRadios',

        props: {
            id: String,
            value: Number,
            options: Array,
            name: String,
        },

        methods: {
            handleChanged(option, e) {
                if(e.target.checked) {
                    this.$emit('input', option.id);
                }
            },
            isChecked(option) {
                return this.value === option.id;
            },
            radioId(option) {
                return `${this.id}-radio-${option.id}`;
            },
        },
    }
</script>
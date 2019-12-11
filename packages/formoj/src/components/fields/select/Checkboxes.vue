<template>
    <div class="fj-checkboxes">
        <template v-for="option in options">
            <div class="fj-checkboxes__check">
                <input type="checkbox"
                    :id="checkboxId(option)"
                    class="fj-checkboxes__check-input"
                    :name="name"
                    :value="option.id"
                    :checked="isChecked(option)"
                    @change="handleChanged(option, $event)"
                >
                <label :for="checkboxId(option)" class="fj-checkboxes__check-label">{{ option.label }}</label>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        name: 'FjCheckboxes',

        props: {
            id: String,
            value: Array,
            options: Array,
            name: String,
            max: Number,
        },

        computed: {
            count() {
                return Array.isArray(this.value)
                    ? this.value.length
                    : 0;
            },
        },

        methods: {
            handleChanged(option, e) {
                if(e.target.checked && this.max && this.count >= this.max) {
                    e.target.checked = false;
                    return;
                }
                this.toggle(option, e.target.checked);
            },
            toggle(option, force) {
                const add = typeof force === 'boolean' ? force : !this.isChecked(option);
                if(add) {
                    this.$emit('input', [ ...(this.value || []), option.id ]);
                } else {
                    this.$emit('input', (this.value || []).filter(id => id !== option.id));
                }
            },
            isChecked(option) {
                return (this.value || []).some(id => id === option.id);
            },
            checkboxId(option) {
                return `${this.id}-check-${option.id}`;
            },
        },
    }
</script>
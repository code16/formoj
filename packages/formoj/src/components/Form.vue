<template>
    <div class="fj-form" :class="classes">
        <div class="fj-form__header">
            <slot name="header">
                <h3 class="fj-form__title">{{ title }}</h3>
                <p class="fj-form__description">{{ description }}</p>
            </slot>
        </div>
        <div class="fj-form__content">
            <template v-if="currentSection">
                <fj-section
                    :fields="currentSection.fields"
                    :title="currentSection.title"
                    :description="currentSection.description"
                    :is-first="isCurrentFirst"
                    :is-last="isCurrentLast"
                    :key="currentSection.id"
                    @submit="handleSubmit"
                    @next="handleNextSectionRequested"
                    @previous="handlePreviousSectionRequested"
                >
                    <template slot="field" slot-scope="{ field }">
                        <fj-field
                            :id="fieldIdAttribute(field)"
                            :value="fieldValue(field)"
                            :name="fieldKey(field)"
                            :field="field"
                            :error="fieldError(field)"
                            @input="handleFieldChanged(field, $event)"
                        />
                    </template>
                </fj-section>
            </template>
        </div>
    </div>
</template>

<script>
    import FjSection from './Section.vue';
    import FjField from './Field.vue';

    export default {
        name: 'FjForm',

        components: {
            FjSection,
            FjField,
        },

        props: {
            title: String,
            description: String,
            sections: Array,
            formId: [Number, String],
            index: Number,
            errors: Object,
            appearance: String,
        },

        data() {
            return {
                data: null,

                message: null,
                messageType: null,

                currentSectionIndex: 0,
            }
        },

        watch: {
            index(index) {
                this.currentSectionIndex = index;
            }
        },

        computed: {
            currentSection() {
                return this.sections[this.currentSectionIndex];
            },
            isCurrentFirst() {
                return this.currentSectionIndex === 0;
            },
            isCurrentLast() {
                return this.currentSectionIndex === this.sections.length - 1;
            },

            classes() {
                return {
                    [`fj-form--${this.appearance}`]: !!this.appearance,
                };
            },
        },

        methods: {
            fieldKey(field) {
                return field.id.toString();
            },
            fieldValue(field) {
                const fieldKey = this.fieldKey(field);
                return this.data
                    ? this.data[fieldKey]
                    : null;
            },
            fieldIdAttribute(field) {
                return `formoj-${this.formId}-field-${field.id}`;
            },
            fieldError(field) {
                const fieldKey = this.fieldKey(field);
                return this.errors
                    ? this.errors[fieldKey]
                    : null;
            },

            handleNextSectionRequested() {
                let cancelled = false;
                const event = {
                    preventDefault: () => cancelled = true,
                };
                this.$emit('next', event, this.currentSection, this.data);
                if(!cancelled) {
                    this.currentSectionIndex++;
                    this.$emit('update:index', this.currentSectionIndex);
                }
            },
            handlePreviousSectionRequested() {
                this.currentSectionIndex--;
                this.$emit('update:index', this.currentSectionIndex);
            },

            handleFieldChanged(field, value) {
                const fieldKey = this.fieldKey(field);
                this.data = {
                    ...this.data,
                    [fieldKey]: value,
                };
            },
            handleSubmit() {
                this.$emit('submit', this.data);
            },
        },
    }
</script>
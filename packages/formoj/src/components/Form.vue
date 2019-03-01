<template>
    <div class="fj-form">
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
                            :value="fieldValue(field)"
                            :field="field"
                            :form-id="formId"
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
            formId: String,
        },

        data() {
            return {
                data: null,

                message: null,
                messageType: null,

                currentSectionIndex: 0,
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
        },

        methods: {
            fieldKey(field) {
                return field.id;
            },
            fieldValue(field) {
                const fieldKey = this.fieldKey(field);
                return this.data
                    ? this.data[fieldKey]
                    : null;
            },

            handleNextSectionRequested() {
                this.currentSectionIndex++;
            },
            handlePreviousSectionRequested() {
                this.currentSectionIndex--;
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
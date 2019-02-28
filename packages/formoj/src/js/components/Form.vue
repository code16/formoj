<template>
    <div class="fj-form">
        <div class="fj-form__header">
            <div class="fj-form__title">
                <slot name="title">
                    <h3>{{ title }}</h3>
                </slot>
            </div>
            <div class="fj-form__description">
                <slot name="description">
                    <p>{{ description }}</p>
                </slot>
            </div>
        </div>
        <div class="fj-form__content">
            <template v-if="currentSection">
                <fj-section
                    :fields="currentSection.fields"
                    :title="currentSection.title"
                    :description="currentSection.description"
                    :is-first="isFirstSection(currentSection)"
                    :is-last="isLastSection(currentSection)"
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
            }
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
            isFirstSection(section) {
                const firstSection = this.sections[0];
                return section.id === firstSection.id;
            },
            isLastSection(section) {
                const lastSection = this.sections[this.sections.length - 1];
                return section.id === lastSection.id;
            },
            nextSectionIndex(section) {
                if(section) {
                    return this.sections.findIndex(s => s.id === section.id) + 1;
                }
                return 0;
            },
            previousSectionIndex(section) {
                if(section) {
                    return this.sections.findIndex(s => s.id === section.id) - 1;
                }
                return this.sections.length - 1;
            },

            handleNextSectionRequested() {
                this.currentSectionIndex = this.nextSectionIndex(this.currentSection);
            },
            handlePreviousSectionRequested() {
                this.currentSectionIndex = this.previousSectionIndex(this.currentSection);
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
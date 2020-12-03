<template>
    <div class="fj-form" :class="classes">
        <div class="fj-form__header" v-if="description || (title && !isTitleHidden)">
            <slot name="header">
                <h3 class="fj-form__title" v-if="!isTitleHidden">{{ title }}</h3>
                <div class="fj-form__description">{{ description }}</div>
            </slot>
        </div>
        <div class="fj-form__content">
            <template v-if="currentSection">
                <fj-section
                    :fields="currentFields"
                    :title="currentSection.title"
                    :is-title-hidden="currentSection.isTitleHidden"
                    :description="currentSection.description"
                    :is-first="isCurrentFirst"
                    :is-last="isCurrentLast"
                    :is-loading="isLoading"
                    :key="currentSection.id"
                    @submit="handleSubmit"
                    @next="handleNextSectionRequested"
                    @previous="handlePreviousSectionRequested"
                >
                    <template v-slot:field="{ field }">
                        <fj-field
                            :id="fieldIdAttribute(field)"
                            :value="fieldValue(field)"
                            :name="fieldKey(field)"
                            :field="field"
                            :form-id="formId"
                            :error="fieldError(field)"
                            @input="handleFieldChanged(field, $event)"
                            @error="handleFieldError(field, $event)"
                            @clear="handleFieldClear(field)"
                        />
                    </template>
                    <template v-slot:indication>
                        <template v-if="isIndicationVisible">{{ currentIndication }}</template>
                    </template>
                </fj-section>
            </template>
        </div>
    </div>
</template>

<script>
    import pick from 'lodash/pick';
    import FjSection from './Section.vue';
    import FjField from './Field.vue';
    import { isVisible } from "../util/visibility";

    export default {
        name: 'FjForm',

        components: {
            FjSection,
            FjField,
        },

        props: {
            title: String,
            isTitleHidden: Boolean,
            description: String,
            sections: Array,
            formId: Number,
            index: Number,
            errors: Object,
            appearance: String,
            isLoading: Boolean,
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
            currentFields() {
                const fields = this.currentSection?.fields ?? [];
                return fields.filter(field => isVisible(field, this.data));
            },
            visibleSections() {
                return this.sections
                    .filter(section => isVisible(section, this.data))
            },
            currentSection() {
                return this.sections[this.currentSectionIndex];
            },
            isCurrentFirst() {
                return this.currentSectionIndex === 0;
            },
            isCurrentLast() {
                return this.currentSectionIndex === this.visibleSections.length - 1;
            },
            isIndicationVisible() {
                return !this.isCurrentLast;
            },
            currentIndication() {
                return `${this.currentSectionIndex + 1}/${this.visibleSections.length}`;
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
                this.$emit('previous');

                this.currentSectionIndex--;
                this.$emit('update:index', this.currentSectionIndex);
            },

            handleFieldChanged(field, value) {
                const fieldKey = this.fieldKey(field);
                const data = {
                    ...this.data,
                    [fieldKey]: value,
                };
                // keep only visible fields
                this.data = pick(data,
                    this.sections.map(s => s.fields).flat()
                        .filter(field => isVisible(field, data))
                        .map(f => f.id)
                );
            },
            handleFieldError(field, message) {
                const fieldKey = this.fieldKey(field);
                this.$emit('error', fieldKey, message);
            },
            handleFieldClear(field) {
                const fieldKey = this.fieldKey(field);
                this.$emit('clear', fieldKey);
            },
            handleSubmit() {
                this.$emit('submit', this.data);
            },
        },
    }
</script>
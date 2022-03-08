<template>
    <div class="fj-form" :class="classes">

        <template v-if="showHeader">
            <div class="fj-form__header">
                <slot name="header">
                    <template v-if="showTitle && title">
                        <h3 class="fj-form__title">{{ title }}</h3>
                    </template>
                    <template v-if="description">
                        <div class="fj-form__description fj-content" v-html="description"></div>
                    </template>
                </slot>
            </div>
        </template>

        <div class="fj-form__content">
            <template v-if="stackSections">
                <div class="fj-form__sections">
                    <template v-for="(section, i) in sections">
                        <template v-if="i">
                            <hr class="fj-divider">
                        </template>
                        <fj-section
                            :fields="section.fields"
                            :title="section.title"
                            :show-title="!section.isTitleHidden"
                            :description="section.description"
                            :show-footer="false"
                            :key="section.id"
                        >
                            <template slot="field" slot-scope="{ field }">
                                <fj-field
                                    inner
                                    v-bind="fieldProps(field)"
                                    v-on="fieldListeners(field)"
                                />
                            </template>
                        </fj-section>
                    </template>
                </div>
                <template v-if="showCancel || showSubmit">
                    <div class="fj-section">
                        <div class="fj-section__footer">
                            <div class="fj-spacer"></div>
                            <div class="fj-section__buttons">
                                <template v-if="showCancel">
                                    <button class="fj-button fj-button--light fj-section__button" style="margin-right: .375em" @click="handleCancel">
                                        {{ $t('section.button.cancel') }}
                                    </button>
                                </template>
                                <template v-if="showSubmit">
                                    <button class="fj-button fj-button--primary fj-section__button fj-section__button--submit" :disabled="isLoading" @click="handleSubmit">
                                        <span>{{ submitButtonLabel || $t('section.button.submit') }}</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <template v-else>
                <template v-if="currentSection">
                    <fj-section
                        :fields="currentSection.fields"
                        :title="currentSection.title"
                        :show-title="!currentSection.isTitleHidden"
                        :description="currentSection.description"
                        :is-first="isCurrentFirst"
                        :is-last="isCurrentLast"
                        :is-loading="isLoading"
                        :show-submit="showSubmit"
                        :submit-button-label="submitButtonLabel"
                        :show-cancel="showCancel"
                        :key="currentSection.id"
                        @submit="handleSubmit"
                        @cancel="handleCancel"
                        @next="handleNextSectionRequested"
                        @previous="handlePreviousSectionRequested"
                    >
                        <template slot="field" slot-scope="{ field }">
                            <fj-field
                                v-bind="fieldProps(field)"
                                v-on="fieldListeners(field)"
                            />
                        </template>
                        <template slot="indication">
                            <template v-if="isIndicationVisible">{{ currentIndication }}</template>
                        </template>
                    </fj-section>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
    import { $t } from '../util/i18n';
    import FjSection from './Section.vue';
    import FjField from './Field.vue';

    export default {
        name: 'FjForm',

        components: {
            FjSection,
            FjField,
        },

        props: {
            value: Object,
            title: String,
            description: String,
            sections: Array,
            formId: Number,
            index: Number,
            errors: Object,
            appearance: {
                type: String,
                default: 'card',
            },
            isLoading: Boolean,
            showTitle: {
                type: Boolean,
                default: true,
            },
            showSubmit: {
                type: Boolean,
                default: true,
            },
            submitButtonLabel: String,
            showCancel: Boolean,

            largeButtons: {
                type: Boolean,
                default: true,
            },
            stackSections: Boolean,
        },

        data() {
            return {
                data: this.value ? { ...this.value } : null,

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
            isIndicationVisible() {
                return this.sections.length > 1;
            },
            currentIndication() {
                return `${this.currentSectionIndex + 1}/${this.sections.length}`;
            },
            showHeader() {
                return !!(this.title && this.showTitle || this.description);
            },
            classes() {
                return {
                    [`fj-form--${this.appearance}`]: !!this.appearance,
                    'fj-form--buttons-large': this.largeButtons,
                };
            },
        },

        methods: {
            $t,
            fieldProps(field) {
                return {
                    id: this.fieldIdAttribute(field),
                    value: this.fieldValue(field),
                    name: this.fieldKey(field),
                    field,
                    formId: this.formId,
                    error: this.fieldError(field),
                }
            },
            fieldListeners(field) {
                return {
                    'input': e => this.handleFieldChanged(field, e),
                    'error': e => this.handleFieldError(field, e),
                    'clear': () => this.handleFieldClear(field),
                }
            },
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
                this.data = {
                    ...this.data,
                    [fieldKey]: value,
                };
                this.$emit('input', { ...this.data });
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
            handleCancel() {
                this.$emit('cancel');
            },
        },
    }
</script>

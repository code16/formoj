<template>
    <div class="formoj" :class="classes">
        <div class="formoj__content">
            <template v-if="hasAlert">
                <div class="formoj__alert-wrapper">
                    <fj-alert :type="messageType">{{ message }}</fj-alert>
                </div>
            </template>
            <template v-if="ready && !isFinished">
                <fj-form
                    v-model="data"
                    :title="form.title"
                    :description="form.description"
                    :sections="form.sections"
                    :form-id="form.id"
                    :index.sync="currentSectionIndex"
                    :errors="validationErrors"
                    :appearance="appearance"
                    :large-buttons="largeButtons"
                    :show-submit="showSubmit"
                    :submit-button-label="submitButtonLabel"
                    :show-title="!form.isTitleHidden"
                    :show-cancel="showCancel"
                    :is-loading="isLoading"
                    @next="handleNextSectionRequested"
                    @previous="handlePreviousSectionRequested"
                    @cancel="handleCancelClicked"
                    @submit="handleFormSubmitted"
                    @error="handleFormFieldError"
                    @clear="handleFormFieldClear"
                />
            </template>
            <template v-if="isLoadingVisible">
                <fj-loading />
            </template>
        </div>
    </div>
</template>

<script>
    import FjForm from './Form';
    import FjAlert from './Alert';
    import FjLoading from './Loading';

    import {getForm, postForm, postSection} from "../api";
    import {config} from "../util/config";
    import {getValidationErrors} from "../util/validation";
    import { isInsideModal, smoothScroll } from "../util/css";
    import {$t} from "../util/i18n";

    export default {
        components: {
            FjForm,
            FjAlert,
            FjLoading,
        },
        props: {
            formId: {
                type: [String, Number],
                required: true,
            },
            appearance: String,
            showSubmit: {
                type: Boolean,
                default: true,
            },
            submitButtonLabel: String,
            showCancel: Boolean,
            showSuccess: {
                type: Boolean,
                default: true,
            },
            loading: Boolean,
            largeButtons: {
                type: Boolean,
                default: true,
            }
        },
        data() {
            return {
                ready: false,
                form: null,
                data: null,
                isLoading: false,
                isFinished: false,

                message: null,
                messageType: null,

                currentSectionIndex: 0,
                validationErrors: null,
            }
        },
        computed: {
            config,
            hasAlert() {
                return !!this.message;
            },
            classes() {
                return {
                    'formoj--empty': !this.ready,
                    'formoj--finished': this.isFinished,
                }
            },
            isLoadingVisible() {
                return this.isLoading || this.loading;
            },
        },
        methods: {
            $t,
            /**
             * @public
             */
            submit({ showSuccess=true } = {}) {
                this.isLoading = true;
                this.resetAlert();
                this.resetValidation();
                return new Promise((resolve, reject) => {
                    postForm(this.config.apiBaseUrl, { formId: this.formId, data: this.data })
                        .then(response => {
                            if (showSuccess) {
                                this.showAlert({
                                    message: response.data.message,
                                    type: 'success',
                                });
                                this.isFinished = true;
                                this.scrollTop();
                            }
                            resolve(response.data);
                        })
                        .catch(error => {
                            reject(error);
                            this.scrollTop();
                            return Promise.reject(error);
                        })
                        .catch(this.handleValidationError)
                        .catch(this.handleUnauthorizedError)
                        .catch(() => {
                            this.showAlert({
                                message: this.$t('form.error.post'),
                                type: 'error',
                            });
                        })
                        .finally(() => {
                            this.isLoading = false;
                        })
                });
            },
            handleFormSubmitted() {
                const event = { preventDefault() { this.defaultPrevented = true } };
                this.$emit('submit', event);
                if(event.defaultPrevented) {
                    return;
                }
                this.submit({
                    showSuccess: this.showSuccess,
                })
                .then(data => {
                    this.$emit('success', data)
                })
                .catch(() => {});
            },
            handleNextSectionRequested(e, currentSection, data) {
                const sectionId = currentSection.id;
                e.preventDefault();
                this.isLoading = true;
                this.resetAlert();
                this.resetValidation();
                postSection(this.config.apiBaseUrl, { formId: this.formId, sectionId, data })
                    .then(() => {
                        this.currentSectionIndex++;
                    })
                    .catch(this.handleValidationError)
                    .catch(this.handleUnauthorizedError)
                    .catch(() => {
                        this.showAlert({
                            message: this.$t('form.error.post'),
                            type: 'error',
                        });
                    })
                    .finally(() => {
                        this.isLoading = false;
                        this.scrollTop();
                    });
            },
            handlePreviousSectionRequested() {
                this.scrollTop();
            },
            handleCancelClicked() {
                this.$emit('cancel');
            },

            handleValidationError(error) {
                if(error.response.status === 422) {
                    this.validationErrors = getValidationErrors(error.response.data);
                    this.showAlert({
                        message: this.$t('form.error.post.invalid'),
                        type: 'error',
                    });
                } else {
                    return Promise.reject(error);
                }
            },

            handleNotAvailableError(error) {
                if(error.response.status === 409) {
                    const data = error.response.data;
                    this.showAlert({
                        message: data.message,
                        type: 'error',
                    });
                } else {
                    return Promise.reject(error);
                }
            },

            handleUnauthorizedError(error) {
                if(error.response.status === 403) {
                    this.showAlert({
                        message: this.$t('form.error.post.unauthorized'),
                        type: 'error',
                    });
                } else {
                    return Promise.reject(error);
                }
            },

            handleFormFieldError(fieldKey, message) {
                this.validationErrors = {
                    ...this.validationErrors,
                    [fieldKey]: message,
                };
            },

            handleFormFieldClear(fieldKey) {
                this.validationErrors = {
                    ...this.validationErrors,
                    [fieldKey]: null,
                };
            },

            scrollTop() {
                if(isInsideModal(this.$el)) {
                    this.$el.scrollIntoView();
                    return;
                }
                const top = this.$el.getBoundingClientRect().top + pageYOffset - this.config.scrollOffset;
                smoothScroll(0, top);
            },

            resetAlert() {
                this.message = null;
                this.messageType = null;
            },
            showAlert({ message, type }) {
                this.message = message;
                this.messageType = type;
            },
            resetValidation() {
                this.validationErrors = null;
            },

            async init() {
                this.isLoading = true;
                this.resetAlert();
                getForm(this.config.apiBaseUrl, { formId: this.formId })
                    .then(form => {
                        this.form = form;
                        this.ready = true;
                    })
                    .catch(this.handleNotAvailableError)
                    .catch(() => {
                        this.showAlert({
                            message: this.$t('form.error.get'),
                            type: 'error',
                        });
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            }
        },
        created() {
            this.init();
        }
    }
</script>

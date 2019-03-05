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
                    :title="form.title"
                    :description="form.description"
                    :sections="form.sections"
                    :form-id="form.id"
                    :index.sync="currentSectionIndex"
                    :errors="validationErrors"
                    :appearance="appearance"
                    @next="handleNextSectionRequested"
                    @previous="handlePreviousSectionRequested"
                    @submit="handleFormSubmitted"
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

    import { getForm, postForm, postSection } from "../api";
    import { config } from "../util/config";
    import { getValidationErrors } from "../util/validation";
    import { $t } from "../util/i18n";

    export default {
        components: {
            FjForm,
            FjAlert,
            FjLoading,
        },
        props: {
            formId: {
                type: String,
                required: true,
            },
            appearance: String,
        },
        data() {
            return {
                ready: false,
                form: null,
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
                return this.isLoading;
            },
        },
        methods: {
            handleFormSubmitted(data) {
                this.isLoading = true;
                this.resetAlert();
                this.resetValidation();
                postForm(this.config.apiBaseUrl, { formId: this.formId, data })
                    .then(response => {
                        this.showAlert({
                            message: response.data.message,
                            type: 'success',
                        });
                        this.isFinished = true;
                    })
                    .catch(this.handleValidationError)
                    .catch(this.handleUnauthorizedError)
                    .catch(() => {
                        this.showAlert({
                            message: $t('form.error.post'),
                            type: 'error',
                        });
                    })
                    .finally(() => {
                        this.isLoading = false;
                        this.scrollTop();
                    });
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
                            message: $t('form.error.post'),
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

            handleValidationError(error) {
                if(error.response.status === 422) {
                    this.validationErrors = getValidationErrors(error.response.data);
                    this.showAlert({
                        message: $t('form.error.post.invalid'),
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
                        message: $t('form.error.post.unauthorized'),
                        type: 'error',
                    });
                } else {
                    return Promise.reject(error);
                }
            },

            scrollTop() {
                this.$el.scrollIntoView({ behavior:'smooth', block:'start' });
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
                            message: $t('form.error.get'),
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
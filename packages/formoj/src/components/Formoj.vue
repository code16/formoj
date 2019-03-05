<template>
    <div class="formoj" :class="classes">
        <template v-if="hasAlert">
            <fj-alert :type="messageType">{{ message }}</fj-alert>
        </template>
        <template v-if="ready">
            <fj-form
                :title="form.title"
                :description="form.description"
                :sections="form.sections"
                :form-id="form.id"
                :index.sync="currentSectionIndex"
                :errors="validationErrors"
                :appearance="appearance"
                @next="handleNextSectionRequested"
                @submit="handleFormSubmitted"
            />
        </template>
        <template v-if="isLoadingVisible">
            <fj-loading />
        </template>
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
                }
            },
            isLoadingVisible() {
                return !this.ready || this.isLoading;
            },
        },
        methods: {
            handleFormSubmitted(data) {
                this.isLoading = true;
                this.resetAlert();
                this.resetValidation();
                postForm(this.config.apiBaseUrl, { formId: this.formId, data })
                    .catch(this.handleValidationError)
                    .catch(() => {
                        this.showAlert({
                            message: $t('form.error.post'),
                            type: 'error',
                        });
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
            handleNextSectionRequested(e, currentSection, data) {
                e.preventDefault();
                this.isLoading = true;
                this.resetAlert();
                this.resetValidation();
                postSection(this.config.apiBaseUrl, {
                    formId: this.formId,
                    sectionId: currentSection.id,
                    data,
                })
                .then(() => {
                    this.currentSectionIndex++;
                })
                .catch(this.handleValidationError)
                .catch(() => {
                    this.showAlert({
                        message: $t('form.error.post'),
                        type: 'error',
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                });
            },

            handleValidationError(error) {
                if(error.response.status === 422) {
                    this.validationErrors = getValidationErrors(error.response.data);
                } else {
                    return Promise.reject(error);
                }
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
                this.ready = false;
                this.resetAlert();
                this.form = await getForm(this.config.apiBaseUrl, { formId: this.formId })
                    .catch(error => {
                        this.showAlert({
                            message: $t('form.error.get'),
                            type: 'error',
                        });
                        return Promise.reject(error);
                    });
                this.ready = true;
            }
        },
        created() {
            this.init();
        }
    }
</script>
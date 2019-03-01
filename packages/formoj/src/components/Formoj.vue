<template>
    <div class="formoj">
        <template v-if="hasAlert">
            <fj-alert :type="messageType">{{ message }}</fj-alert>
        </template>
        <template v-if="ready">
            <fj-form
                :title="form.title"
                :description="form.description"
                :sections="form.sections"
                :form-id="form.id"
                :appearance="appearance"
                @submit="handleSubmit"
            />
        </template>
    </div>
</template>

<script>
    import FjForm from './Form';
    import FjAlert from './Alert';

    import {config} from "../util/config";
    import {getForm, postForm} from "../api";
    import {$t} from "../util/i18n";

    export default {
        components: {
            FjForm,
            FjAlert,
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

                message: null,
                messageType: null,
            }
        },
        computed: {
            config,
            hasAlert() {
                return !!this.message;
            }
        },
        methods: {
            handleSubmit(data) {
                this.resetAlert();
                postForm(this.config.apiBaseUrl, { formId: this.formId, data })
                    .catch(() => {
                        this.showAlert({
                            message: $t('form.error.post'),
                            type: 'error',
                        });
                    });
            },

            resetAlert() {
                this.message = null;
                this.messageType = null;
            },
            showAlert({ message, type }) {
                this.message = message;
                this.messageType = type;
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
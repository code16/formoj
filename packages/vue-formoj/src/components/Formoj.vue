<template>
    <div class="formoj">
        <template v-if="ready">
            <template v-if="hasAlert">
                <fj-alert :type="messageType">{{ message }}</fj-alert>
            </template>
            <fj-form
                :title="form.title"
                :description="form.description"
                :sections="form.sections"
                :form-id="form.id"
            />
        </template>
    </div>
</template>

<script>
    import FjForm from './Form';
    import FjAlert from './Alert';

    import { config } from "../util/config";
    import { getForm, postForm } from "../api";
    import { $t } from "../util/i18n";

    export default {
        components: {
            FjForm,
            FjAlert,
        },
        props: {
            formId: {
                type: String,
                required: true,
            }
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
            handleSubmit() {
                postForm(this.config.baseApiUrl, { formId: this.formId });
            },

            resetAlert() {
                this.message = null;
                this.messageType = null;
            },
            showAlert({ message, type }) {
                this.message = message;
                this.messageType = type;
            },

            init() {
                this.ready = false;
                this.resetAlert();
                return getForm(this.config.baseApiUrl, { formId: this.formId })
                    .then(form => {
                        this.form = form;
                    })
                    .catch(() => {
                        this.showAlert({
                            message: $t('form.error.network'),
                            type: 'error',
                        });
                    })
                    .finally(() => {
                        this.ready = true;
                    });
            }
        },
        created() {
            this.init();
        }
    }
</script>
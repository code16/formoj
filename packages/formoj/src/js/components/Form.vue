<template>
    <div class="fj-form">
        <template v-if="ready">

        </template>
    </div>
</template>

<script>
    import { getForm, postForm } from "../api";
    import { $t } from "../util/i18n";

    export default {
        name: 'FjForm',

        props: {
            endpoint: {
                type: String,
                default: '/formoj',
            },
            formId: {
                type: [String, Number],
                required: true,
            }
        },

        data() {
            return {
                ready: false,
                form: null,
                error: null,
            }
        },

        computed: {
            sections() {
                return this.form.sections;
            },
        },

        methods: {
            post() {
                postForm(this.endpoint, { formId: this.formId });
            },
            async init() {
                return getForm(this.endpoint, { formId: this.formId })
                    .then(form => {
                        this.form = form;
                    })
                    .catch(error => {
                        this.error = $t('form.error.network');
                        return Promise.reject(error);
                    })
                    .finally(() => {
                        this.ready = true;
                    });
            },
        },
        created() {
            this.init();
        },
    }
</script>
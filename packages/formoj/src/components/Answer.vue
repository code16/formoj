<template>
    <div class="formoj formoj--answer" :class="classes">
        <div class="formoj__content">
            <template v-if="errorMessage">
                <div class="formoj__alert-wrapper">
                    <fj-alert type="error">{{ errorMessage }}</fj-alert>
                </div>
            </template>
            <template v-if="loading">
                <fj-loading />
            </template>
            <template v-if="ready">
                <div class="fj-answer">
                    <div class="fj-answer__content">
                        <dl style="margin-bottom: 0">
                            <template v-for="(value, key) in filteredData">
                                <dt>{{ label(key) }}</dt>
                                <dd>
                                    <template v-if="isList(value)">
                                        <ul>
                                            <template v-for="item in value">
                                                <li>{{ item }}</li>
                                            </template>
                                        </ul>
                                    </template>
                                    <template v-else>
                                        {{ value }}
                                    </template>
                                </dd>
                            </template>
                        </dl>
                    </div>
                    <template v-if="$slots.footer">
                        <div class="fj-answer__footer">
                            <slot name="footer" />
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import { getAnswer } from "../api";
    import { $t } from '../util/i18n';
    import { config } from "../util/config";
    import FjAlert from "./Alert";
    import FjLoading from './Loading';

    export default {
        components: {
            FjAlert,
            FjLoading,
        },
        props: {
            answerId: {
                type: [String, Number],
                required: true,
            },
            showEmpty: Boolean,
        },
        data() {
            return {
                answer: null,
                ready: false,
                loading: false,
                errorMessage: null,
            }
        },
        computed: {
            config,
            classes() {
                return {
                    'formoj--empty': !this.ready,
                }
            },
            filteredData() {
                return Object.fromEntries(
                    Object.entries(this.answer.content)
                        .filter(([key, value]) => {
                            if(!this.showEmpty && (value == null || value === '' || value?.length === 0)) {
                                return false;
                            }
                            return true;
                        })
                )
            },
        },
        methods: {
            $t,
            field(key) {
                return this.answer.fields.find(field => field.key === key);
            },
            isList(value) {
                return Array.isArray(value);
            },
            label(key) {
                return this.field(key)?.label ?? key;
            },
            async init() {
                this.loading = true;
                this.answer = await getAnswer(this.config.apiBaseUrl, {
                    answerId: this.answerId,
                })
                .catch(error => {
                    this.errorMessage = this.$t('answer.error.get');
                    return Promise.reject(error);
                })
                .finally(() => {
                    this.loading = false;
                })
                this.ready = true;
            },
        },
        created() {
            this.init();
        },
    }
</script>

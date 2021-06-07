<template>
    <div class="formoj" :class="classes">
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
                <div class="fj-answer" :class="answerClasses">
                    <div class="fj-answer__content">
                        <template v-if="isEmpty">
                            <div class="fj-answer__empty">
                                <slot name="empty">
                                    {{ $t('answer.empty') }}
                                </slot>
                            </div>
                        </template>
                        <template v-else>
                            <dl style="margin-bottom: 0">
                                <template v-for="(value, name) in filteredData">
                                    <dt>{{ label(name) }}</dt>
                                    <dd>
                                        <template v-if="isList(value)">
                                            <ul>
                                                <template v-for="item in value">
                                                    <li>{{ item }}</li>
                                                </template>
                                            </ul>
                                        </template>
                                        <template v-else>
                                            <!-- must be single line -->
                                            <div class="fj-answer__text">{{ value }}</div>
                                        </template>
                                    </dd>
                                </template>
                            </dl>
                        </template>
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
            appearance: {
                type: String,
                default: 'card',
            },
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
                    [`formoj--appearance-${this.appearance}`]: !!this.appearance,
                }
            },
            answerClasses() {
                return {
                    [`fj-answer--${this.appearance}`]: !!this.appearance,
                }
            },
            isEmpty() {
                return Object.keys(this.filteredData).length === 0;
            },
            filteredData() {
                return Object.fromEntries(
                    Object.entries(this.answer.content)
                        .filter(([name, value]) => {
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
            field(name) {
                return this.answer.fields.find(field => field.name === name);
            },
            isList(value) {
                return Array.isArray(value);
            },
            label(fieldName) {
                return this.field(fieldName)?.label ?? fieldName;
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

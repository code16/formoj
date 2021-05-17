<template>
    <div class="fj-section">
        <div class="fj-section__header">
            <slot name="header">
                <template v-if="showTitle && title">
                    <h4 class="fj-section__title">{{ title }}</h4>
                </template>
                <template v-if="description">
                    <div class="fj-section__description">{{ description }}</div>
                </template>
            </slot>
        </div>
        <div class="fj-section__fields">
            <template v-for="field in fields">
                <div class="fj-section__field" :key="field.id">
                    <slot name="field" :field="field"  />
                </div>
            </template>
        </div>

        <template v-if="footerVisible">
            <div class="fj-section__footer">
                <div class="fj-section__indication">
                    <slot name="indication"/>
                </div>
                <div class="fj-section__buttons">
                    <template v-if="showCancel">
                        <button class="fj-button fj-button--light fj-section__button" style="margin-right: .375em" @click="handleCancelButtonClicked">
                            {{ $t('section.button.cancel') }}
                        </button>
                    </template>
                    <template v-if="!isFirst">
                        <button class="fj-button fj-button--light fj-section__button" :disabled="isLoading" @click="handlePreviousButtonClicked">
                            <span class="fj-icon" style="opacity: .5">&lsaquo;</span>
                            <span>{{ $t('section.button.previous') }}</span>
                        </button>
                    </template>
                    <template v-if="isLast">
                        <template v-if="showSubmit">
                            <button class="fj-button fj-button--primary fj-section__button fj-section__button--submit" :disabled="isLoading" @click="handleSubmitButtonClicked">
                                <span>{{ submitButtonLabel || $t('section.button.submit') }}</span>
                            </button>
                        </template>
                    </template>
                    <template v-else>
                        <button class="fj-button fj-button--primary fj-section__button" :disabled="isLoading" @click="handleNextButtonClicked">
                            <span>{{ $t('section.button.next') }}</span>
                            <span class="fj-icon">&rsaquo;</span>
                        </button>
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import FjField from './Field';
    import {$t} from "../util/i18n";

    export default {
        name: 'FjSection',

        components: {
            FjField,
        },

        props: {
            fields: Array,
            title: String,
            showTitle: {
                type: Boolean,
                default: true,
            },
            description: String,
            isFirst: Boolean,
            isLast: Boolean,
            isLoading: Boolean,
            showSubmit: {
                type: Boolean,
                default: true,
            },
            showCancel: Boolean,
            submitButtonLabel: String,
            showFooter: {
                type: Boolean,
                default: true,
            }
        },

        computed: {
            footerVisible() {
                return this.showFooter && (!!this.$slots.indication || this.showSubmit);
            },
        },

        methods: {
            $t,
            handleCancelButtonClicked() {
                this.$emit('cancel');
            },
            handleSubmitButtonClicked() {
                this.$emit('submit');
            },
            handlePreviousButtonClicked() {
                this.$emit('previous');
            },
            handleNextButtonClicked() {
                this.$emit('next');
            },
        },
    }
</script>

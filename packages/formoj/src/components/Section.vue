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

        <template v-if="showFooter">
            <div class="fj-section__footer">
                <div class="fj-section__indication">
                    <slot name="indication"/>
                </div>
                <div class="fj-section__buttons">
                    <template v-if="showCancel">
                        <button class="fj-button fj-button--light fj-section__button" @click="handleCancelButtonClicked">
                            {{ $t('section.button.next') }}
                        </button>
                    </template>
                    <template v-if="!isFirst">
                        <button class="fj-button fj-button--light fj-section__button" :disabled="isLoading" @click="handlePreviousButtonClicked">
                            {{ $t('section.button.previous') }}
                        </button>
                    </template>
                    <template v-if="isLast">
                        <button class="fj-button fj-button--primary fj-section__button" :disabled="isLoading" @click="handleSubmitButtonClicked">
                            {{ $t('section.button.submit') }}
                        </button>
                    </template>
                    <template v-else-if="showSubmit">
                        <button class="fj-button fj-button--primary fj-section__button" :disabled="isLoading" @click="handleNextButtonClicked">
                            {{ $t('section.button.next') }}
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
            showSubmit: Boolean,
            showCancel: Boolean,
        },

        computed: {
            showFooter() {
                return !!this.$slots.indication || !this.isFirst || this.showSubmit;
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

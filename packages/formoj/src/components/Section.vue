<template>
    <div class="fj-section">
        <div class="fj-section__header">
            <slot name="header">
                <h4 class="fj-section__title">{{ title }}</h4>
                <p class="fj-section__description">{{ description }}</p>
            </slot>
        </div>

        <template v-for="field in fields">
            <div class="fj-section__fields" :key="field.id">
                <slot name="field" :field="field" />
            </div>
        </template>

        <div class="fj-section__footer">
            <template v-if="!isFirst">
                <button class="fj-button fj-button--secondary fj-section__button" @click="handlePreviousButtonClicked">
                    {{ $t('section.button.previous') }}
                </button>
            </template>
            <template v-if="isLast">
                <button class="fj-button fj-button--primary fj-section__button" @click="handleSubmitButtonClicked">
                    {{ $t('section.button.submit') }}
                </button>
            </template>
            <template v-else>
                <button class="fj-button fj-button--primary fj-section__button" @click="handleNextButtonClicked">
                    {{ $t('section.button.next') }}
                </button>
            </template>
        </div>
    </div>
</template>

<script>
    import FjField from './Field';
    import { $t } from "../util/i18n";

    export default {
        name: 'FjSection',

        components: {
            FjField,
        },

        props: {
            fields: Array,
            title: String,
            description: String,
            isFirst: Boolean,
            isLast: Boolean,
        },

        methods: {
            $t,
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
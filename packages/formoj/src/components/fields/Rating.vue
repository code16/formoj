<template>
    <div class="fj-radios fj-rating">
        <div class="fj-rating__body">
            <div class="fj-rating__content">
                <template v-if="lowestLabel">
                    <div class="fj-rating__label fj-rating__label--lowest">
                        1 = {{ lowestLabel }}
                    </div>
                </template>
                <div class="fj-rating__list">
                    <template v-for="rating in Array.from({length: 5}, (x, i) => i + 1)">
                        <label class="fj-rating__item">
                            <span class="fj-radios__check fj-rating__check">
                                <input type="radio"
                                    class="fj-radios__check-input"
                                    :name="name"
                                    :checked="value === rating"
                                    @change="$emit('input', rating)"
                                >
                                <span class="fj-radios__check-label">
                                    {{ rating }}
                                </span>
                            </span>
                        </label>
                    </template>
                </div>
                <template v-if="highestLabel">
                    <div class="fj-rating__label fj-rating__label--highest">
                        5 = {{ highestLabel }}
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'FjRating',

        props: {
            id: String,
            value: Number,
            name: String,
            lowestLabel: String,
            highestLabel: String,
        },
    }
</script>

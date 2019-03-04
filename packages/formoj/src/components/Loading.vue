<template>
    <div class="fj-loading" role="status">
        <div class="fj-loading__indicator"></div>
        <span class="sr-only">{{ $t('loading') }}</span>
    </div>
</template>

<script>
    import { $t } from "../util/i18n";

    export default {
        methods : {
            $t,
        },
    }
</script>
<template>
    <div class="fj-upload">
        <div class="fj-upload__control" ref="control">
            <!-- hidden file input here -->
            <label class="fj-upload__label" :data-browse="$t('field.upload.browse')">
                {{ label }}
            </label>
            <Dropzone
                class="fj-upload__dropzone"
                :id="dropzoneId"
                :options="options"
                @vdropzone-file-added="handleFileAdded"
                @vdropzone-error="handleError"
                ref="dropzone"
            />
        </div>
    </div>
</template>

<script>
    import { getXsrfToken } from "../../util/xhr";
    import { $t } from "../../util/i18n";
    import Dropzone from 'vue2-dropzone';

    export default {
        name: 'FjUpload',

        components: {
            Dropzone,
        },

        props: {
            id: String,
            url: String,
            acceptedFiles: String,
            maxFilesize: Number,
        },

        data() {
            return {
                file: null,
            }
        },

        computed: {
            options() {
                return {
                    url: this.url,
                    acceptedFiles: this.acceptedFiles,
                    maxFilesize: this.maxFilesize || .1,
                    headers: {
                        'X-XSRF-TOKEN': getXsrfToken(),
                    },
                    hiddenInputContainer: `#${this.dropzoneId}`,
                    dictFileTooBig: 'max_size',
                    dict
                }
            },
            label() {
                return this.file
                    ? this.file.name
                    : this.$t('field.upload.placeholder');
            },
            dropzoneId() {
                return `${this.id}-dropzone`;
            },
        },

        methods: {
            $t,
            handleFileAdded(file) {
                this.file = file;
            },
            handleError() {
                console.log(arguments);
            }
        },

        mounted() {
            const input = this.$el.querySelector('.dz-hidden-input');
            input.id = this.id;
            this.$refs.control.insertBefore(input, this.$refs.control.firstChild);
        }
    }
</script>
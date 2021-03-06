<template>
    <div class="fj-upload">
        <div class="fj-upload__control">
            <Dropzone
                class="fj-upload__input"
                :id="dropzoneId"
                :options="options"
                tabindex="0"
                @vdropzone-file-added="handleFileAdded"
                @vdropzone-upload-progress="handleUploadProgress"
                @vdropzone-complete="handleComplete"
                @vdropzone-sending="handleSending"
                @vdropzone-error="handleError"
                @vdropzone-success="handleSuccess"
                ref="dropzone"
            />
            <div class="fj-upload__label" :data-browse="$t('field.upload.browse')">
                {{ label }}
            </div>
        </div>
        <template v-if="isUploading">
            <div class="fj-upload__progress">
                <div class="fj-upload__progress-bar" role="progressbar" :style="{ width: `${progress}%` }" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </template>
    </div>
</template>

<script>
    import Dropzone from 'vue2-dropzone';
    import { getXsrfToken } from "../../util/xhr";
    import { $t } from "../../util/i18n";
    import { config } from "../../util/config";
    import { postUploadUrl } from "../../api";
    import { getValidationErrors } from "../../util/validation";

    export default {
        name: 'FjUpload',

        components: {
            Dropzone,
        },

        props: {
            id: String,
            value: Object,
            accept: String,
            maxSize: Number,
            formId: Number,
            name: String,
        },

        data() {
            return {
                file: null,
                isUploading: false,
                progress: null,
            }
        },

        watch: {
            value: {
                immediate: true,
                handler: 'handleValueChanged',
            }
        },

        computed: {
            config,
            options() {
                return {
                    url: postUploadUrl(this.config.apiBaseUrl, {
                        formId: this.formId,
                        fieldId: this.name,
                    }),
                    acceptedFiles: this.accept,
                    maxFilesize: this.maxSize,
                    headers: {
                        'X-XSRF-TOKEN': getXsrfToken(),
                    },
                    dictFileTooBig: this.$t('field.upload.error.max_size', {
                        max: this.maxSize,
                    }),
                    dictInvalidFileType: this.$t('field.upload.error.invalid_type', {
                        extensions: this.accept,
                    }),
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
            handleValueChanged() {
                if(this.value) {
                    this.file = {
                        name: this.value.file,
                    }
                } else {
                    this.file = null;
                }
            },
            handleFileAdded(file) {
                this.file = file;
            },
            handleError(file, error, xhr) {
                if(xhr && xhr.status === 422) {
                    const errors = getValidationErrors(error);
                    this.$emit('error', errors.file);
                } else {
                    this.$emit('error', error);
                }
            },
            handleUploadProgress(file, progress) {
                this.progress = progress;
            },
            handleSending() {
                this.isUploading = true;
                this.$emit('clear');
            },
            handleComplete() {
                this.isUploading = false;
                this.progress = null;
            },
            handleSuccess(file, responseData) {
                this.$emit('input', responseData);
            },
            handleInputClicked() {
                // when the file input is clicked (e.g. click on form label), ensure dropzone div is focused
                this.$refs.dropzone.$el.focus();
            },
        },

        mounted() {
            const fileInput = this.$refs.dropzone.dropzone.hiddenFileInput;
            fileInput.id = this.id;
            fileInput.addEventListener('click', this.handleInputClicked);
        },
    }
</script>

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
            <label class="fj-upload__label" :data-browse="$t('field.upload.browse')">
                {{ label }}
            </label>
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

        computed: {
            config,
            options() {
                return {
                    url: postUploadUrl(this.config.apiBaseUrl, {
                        formId: this.formId,
                        fieldId: this.name,
                    }),
                    acceptedFiles: this.accept,
                    maxFilesize: this.maxSize || .1,
                    headers: {
                        'X-XSRF-TOKEN': getXsrfToken(),
                    },
                    dictFileTooBig: this.$t('field.upload.error.max_size', {
                        max: this.maxFilesize,
                    }),
                    dictInvalidFileType: this.$t('field.upload.error.invalid_type', {
                        extensions: this.acceptedFiles,
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
            handleFileAdded(file) {
                this.file = file;
            },
            handleError(file, error, xhr) {
                if(xhr && xhr.status === 422) {
                    const errors = getValidationErrors(error);
                    this.$emit('error', errors[this.name]);
                } else {
                    this.$emit('error', error);
                }
            },
            handleUploadProgress(file, progress) {
                this.progress = progress;
            },
            handleSending() {
                this.isUploading = true;
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
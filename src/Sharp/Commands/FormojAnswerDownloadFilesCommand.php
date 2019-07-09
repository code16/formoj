<?php

namespace Code16\Formoj\Sharp\Commands;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Section;
use Code16\Sharp\EntityList\Commands\InstanceCommand;
use Code16\Sharp\Exceptions\Form\SharpApplicativeException;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class FormojAnswerDownloadFilesCommand extends InstanceCommand
{

    /**
     * @return string
     */
    public function label(): string
    {
        return trans("formoj::sharp.answers.commands.download_files");
    }

    /**
     * @param string $instanceId
     * @param array $data
     * @return array
     * @throws SharpApplicativeException
     */
    public function execute($instanceId, array $data = []): array
    {
        $answer = Answer::with("form", "form.sections", "form.sections.fields")
            ->findOrFail($instanceId);

        $uploadFields = $answer->form->sections
            ->map(function(Section $section) {
                return $section->fields;
            })
            ->flatten()
            ->filter->isTypeUpload();

        if(!$uploadFields->count()) {
            throw new SharpApplicativeException(trans("formoj::sharp.answers.errors.no_file_to_download"));
        }

        if($uploadFields->count() == 1) {
            // Only one field to download
            if(!$filename = $answer->content($uploadFields->first()->label)) {
                throw new SharpApplicativeException(trans("formoj::sharp.answers.errors.no_file_to_download"));
            }

            return $this->download(
                config("formoj.storage.path") . "/{$answer->form_id}/answers/{$answer->id}/$filename",
                $filename,
                config("formoj.storage.disk")
            );

        } else {
            // Multiple files: we must create a Zip archive
            $archiveName = $this->createArchive($answer);

            return $this->download(
                $archiveName,
                basename($archiveName),
                config("formoj.upload.disk")
            );
        }
    }

    /**
     * @param Answer $answer
     * @return string
     */
    private function createArchive(Answer $answer)
    {
        $archiveFilePath = config("formoj.upload.path") . "/archive-" . $answer->id . ".zip";

        $zip = new ZipArchive();
        $zip->open(
            Storage::disk(config("formoj.upload.disk"))->path($archiveFilePath),
            ZipArchive::CREATE | ZipArchive::OVERWRITE
        );

        $answerFilesPath = config("formoj.storage.path") . "/{$answer->form_id}/answers/{$answer->id}";
        collect(Storage::disk(config("formoj.storage.disk"))->files($answerFilesPath))
            ->each(function($file) use($zip) {
                $zip->addFile(
                    Storage::disk(config("formoj.storage.disk"))->path($file),
                    basename($file)
                );
            });

        $zip->close();

        return $archiveFilePath;
    }
}
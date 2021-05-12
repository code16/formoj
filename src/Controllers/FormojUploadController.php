<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Controllers\Requests\UploadRequest;
use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;
use Illuminate\Filesystem\FilesystemManager;

class FormojUploadController
{
    protected FilesystemManager $fileSystem;

    public function __construct(FilesystemManager $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function store(Form $form, Field $field, UploadRequest $request)
    {
        $path = $request
            ->file('file')
            ->storeAs(
                config("formoj.upload.path") . "/{$form->id}",
                $this->getStoreFileName($request->file, config("formoj.upload.path") . "/{$form->id}"),
                config("formoj.upload.disk")
            );

        return response()->json(["file" => basename($path)]);
    }

    protected function getStoreFileName($file, $folder): string
    {
        $filename = $file->getClientOriginalName();
        $disk = $this->fileSystem->disk(config("formoj.upload.disk"));

        for($k=1; $disk->exists("/$folder/$filename"); $k++) {
            $filename = explode(".", $file->getClientOriginalName())[0]
                . "-{$k}."
                . $file->getClientOriginalExtension();
        }

        return $filename;
    }
}
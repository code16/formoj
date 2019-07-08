<?php

namespace Code16\Formoj\Tests\Feature;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FormojUploadControllerTest extends FormojTestCase
{
    use RefreshDatabase;

    /** @test */
    function we_can_upload_a_file()
    {
        Storage::fake('local');

        $field = factory(Field::class)->create([
            "type" => Field::TYPE_UPLOAD,
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/upload/{$field->id}", [
                "file" => UploadedFile::fake()->image('image.jpg')
            ])
            ->assertStatus(200)
            ->assertJson(["file" => "image.jpg"]);

        Storage::disk('local')->assertExists("formoj/tmp/{$field->section->form_id}/image.jpg");
    }

    /** @test */
    function we_cant_upload_an_invalid_file()
    {
        Storage::fake('local');

        $field = factory(Field::class)->create([
            "type" => Field::TYPE_UPLOAD,
            "required" => true,
            "field_attributes->max_size" => 1,
            "field_attributes->accept" => ".jpeg",
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/upload/{$field->id}", [
                "file" => UploadedFile::fake()->image('image.jpg')->size(2*1024)
            ])
            ->assertStatus(422);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/upload/{$field->id}", [
                "file" => UploadedFile::fake()->create('doc.pdf')->size(512)
            ])
            ->assertStatus(422);
    }

    /** @test */
    function we_cant_upload_a_file_for_an_invalid_field()
    {
        Storage::fake('local');

        $field = factory(Field::class)->create([
            "type" => Field::TYPE_TEXT,
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/upload/{$field->id}", [
                "file" => UploadedFile::fake()->image('image.jpg')
            ])
            ->assertStatus(403);
    }

    /** @test */
    function the_uploaded_file_name_is_suffixed_if_needed()
    {
        $this->withoutExceptionHandling();

        Storage::fake('local');

        $field = factory(Field::class)->create([
            "type" => Field::TYPE_UPLOAD,
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        UploadedFile::fake()->image('image.jpg')->storeAs("formoj/tmp/{$field->section->form_id}", "image.jpg", "local");

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/upload/{$field->id}", [
                "file" => UploadedFile::fake()->image('image.jpg')
            ])
            ->assertStatus(200)
            ->assertJson(["file" => "image-1.jpg"]);

        Storage::disk('local')->assertExists("formoj/tmp/{$field->section->form_id}/image-1.jpg");
    }
}
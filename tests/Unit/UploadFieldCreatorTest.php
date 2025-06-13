<?php

namespace Code16\Formoj\Tests\Unit;

use Code16\Formoj\Models\Creators\UploadFieldCreator;
use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadFieldCreatorTest extends FormojTestCase
{
    use RefreshDatabase;

    /** @test */
    function we_can_create_a_new_default_upload_field()
    {
        (new UploadFieldCreator(Section::factory()->create(), "test"))
            ->create();

        $this->assertDatabaseHas("formoj_fields", [
            "type" => Field::TYPE_UPLOAD,
            "label" => "test",
            "required" => 0,
            "help_text" => null,
            "field_attributes" => json_encode([
                "max_size" => 8,
                "accept" => ""
            ])
        ]);
    }

    /** @test */
    function we_can_create_a_new_custom_upload_field()
    {
        (new UploadFieldCreator(Section::factory()->create(), "test"))
            ->setRequired()
            ->setHelpText("help")
            ->setMaxSize(4)
            ->setAccept([".jpg", ".gif"])
            ->create();

        $this->assertDatabaseHas("formoj_fields", [
            "type" => Field::TYPE_UPLOAD,
            "label" => "test",
            "required" => 1,
            "help_text" => "help",
            "field_attributes" => json_encode([
                "max_size" => 4,
                "accept" => ".jpg,.gif",
            ])
        ]);
    }
}

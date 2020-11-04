<?php

namespace Code16\Formoj\Tests\Unit;

use Code16\Formoj\Models\Creators\TextareaFieldCreator;
use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TextareaFieldCreatorTest extends FormojTestCase
{
    use RefreshDatabase;

    /** @test */
    function we_can_create_a_new_default_textarea_field()
    {
        (new TextareaFieldCreator(Section::factory()->create(), "test"))
            ->create();

        $this->assertDatabaseHas("formoj_fields", [
            "type" => Field::TYPE_TEXTAREA,
            "label" => "test",
            "required" => 0,
            "help_text" => null,
            "field_attributes" => json_encode([
                "max_length" => null,
                "rows_count" => 3,
            ])
        ]);
    }

    /** @test */
    function we_can_create_a_new_custom_textarea_field()
    {
        (new TextareaFieldCreator(Section::factory()->create(), "test"))
            ->setRequired()
            ->setHelpText("help")
            ->setMaxLength(120)
            ->setRowsCount(5)
            ->create();

        $this->assertDatabaseHas("formoj_fields", [
            "type" => Field::TYPE_TEXTAREA,
            "label" => "test",
            "required" => 1,
            "help_text" => "help",
            "field_attributes" => json_encode([
                "max_length" => 120,
                "rows_count" => 3,
            ])
        ]);
    }
}
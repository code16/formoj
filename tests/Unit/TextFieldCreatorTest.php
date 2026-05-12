<?php

namespace Code16\Formoj\Tests\Unit;

use Code16\Formoj\Models\Creators\TextFieldCreator;
use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class TextFieldCreatorTest extends FormojTestCase
{
    use RefreshDatabase;

    #[Test]
    function we_can_create_a_new_default_text_field()
    {
        (new TextFieldCreator(Section::factory()->create(), "test"))
            ->create();

        $this->assertDatabaseHas("formoj_fields", [
            "type" => Field::TYPE_TEXT,
            "label" => "test",
            "required" => 0,
            "help_text" => null,
            "field_attributes" => json_encode([
                "max_length" => null
            ])
        ]);
    }

    #[Test]
    function we_can_create_a_new_custom_text_field()
    {
        (new TextFieldCreator(Section::factory()->create(), "test"))
            ->setRequired()
            ->setHelpText("help")
            ->setMaxLength(12)
            ->create();

        $this->assertDatabaseHas("formoj_fields", [
            "type" => Field::TYPE_TEXT,
            "label" => "test",
            "required" => 1,
            "help_text" => "help",
            "field_attributes" => json_encode([
                "max_length" => 12
            ])
        ]);
    }
}

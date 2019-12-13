<?php

namespace Code16\Formoj\Tests\Unit;

use Code16\Formoj\Models\Creators\SelectFieldCreator;
use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SelectFieldCreatorTest extends FormojTestCase
{
    use RefreshDatabase;

    /** @test */
    function we_can_create_a_new_default_select_field()
    {
        (new SelectFieldCreator(factory(Section::class)->create(), "test", ["a","b"]))
            ->create();

        $this->assertDatabaseHas("formoj_fields", [
            "type" => Field::TYPE_SELECT,
            "label" => "test",
            "required" => 0,
            "help_text" => null,
            "field_attributes" => json_encode([
                "options" => ["a","b"],
                "multiple" => false,
                "radios" => false,
                "max_options" => null,
            ])
        ]);
    }

    /** @test */
    function we_can_create_a_new_custom_select_field()
    {
        (new SelectFieldCreator(factory(Section::class)->create(), "test", ["a","b"]))
            ->setRequired()
            ->setHelpText("help")
            ->setOptions(["a","b","c"])
            ->create();

        $this->assertDatabaseHas("formoj_fields", [
            "type" => Field::TYPE_SELECT,
            "label" => "test",
            "required" => 1,
            "help_text" => "help",
            "field_attributes" => json_encode([
                "options" => ["a","b","c"],
                "multiple" => false,
                "radios" => false,
                "max_options" => null,
            ])
        ]);
    }

    /** @test */
    function we_can_create_a_new_custom_multiple_select_field()
    {
        (new SelectFieldCreator(factory(Section::class)->create(), "test", ["a","b"]))
            ->setRequired()
            ->setHelpText("help")
            ->setOptions(["a","b","c"])
            ->setMultiple()
            ->setMaxOptions(2)
            ->create();

        $this->assertDatabaseHas("formoj_fields", [
            "type" => Field::TYPE_SELECT,
            "label" => "test",
            "required" => 1,
            "help_text" => "help",
            "field_attributes" => json_encode([
                "options" => ["a","b","c"],
                "multiple" => true,
                "radios" => false,
                "max_options" => 2,
            ])
        ]);
    }

    /** @test */
    function we_can_create_a_new_custom_radios_select_field()
    {
        (new SelectFieldCreator(factory(Section::class)->create(), "test", ["a","b"]))
            ->setRequired()
            ->setHelpText("help")
            ->setOptions(["a","b","c"])
            ->setMultiple() // should be ignored
            ->setRadios(true)
            ->create();

        $this->assertDatabaseHas("formoj_fields", [
            "type" => Field::TYPE_SELECT,
            "label" => "test",
            "required" => 1,
            "help_text" => "help",
            "field_attributes" => json_encode([
                "options" => ["a","b","c"],
                "multiple" => false,
                "radios" => true,
                "max_options" => null,
            ])
        ]);
    }

}
<?php

namespace Code16\Formoj\Tests\Feature;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormojFormControllerTest extends FormojTestCase
{
    use RefreshDatabase;

    /** @test */
    function we_cant_get_a_non_existing_form()
    {
        $this->get("/formoj/api/form/1")
            ->assertStatus(404);
    }

    /** @test */
    function we_can_get_a_form()
    {
        $field = factory(Field::class)->create([
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this->get("/formoj/api/form/{$field->section->form_id}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "title" => $field->section->form->title,
                    "description" => $field->section->form->description,
                    "sections" => [
                        [
                            "id" => $field->section->id,
                            "title" => $field->section->title,
                            "description" => $field->section->description,
                            "fields" => [
                                [
                                    "id" => $field->id,
                                    "type" => $field->type,
                                    "helpText" => $field->description,
                                    "label" => $field->label,
                                    "required" => $field->required
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    function we_can_get_a_form_with_a_text_field()
    {
        $field = factory(Field::class)->create([
            "type" => "text",
            "max_length" => 10,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this->get("/formoj/api/form/{$field->section->form_id}")
            ->assertStatus(200)
            ->assertJsonFragment([
                "fields" => [
                    [
                        "id" => $field->id,
                        "type" => "text",
                        "label" => $field->label,
                        "helpText" => $field->description,
                        "required" => $field->required,
                        "maxlength" => 10,
                    ]
                ]
            ]);
    }

    /** @test */
    function we_can_get_a_form_with_a_textarea_field()
    {
        $field = factory(Field::class)->create([
            "type" => "textarea",
            "max_length" => 10,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this->get("/formoj/api/form/{$field->section->form_id}")
            ->assertStatus(200)
            ->assertJsonFragment([
                "fields" => [
                    [
                        "id" => $field->id,
                        "type" => "textarea",
                        "label" => $field->label,
                        "rows" => $field->rows_count,
                        "helpText" => $field->description,
                        "required" => $field->required,
                        "maxlength" => 10,
                    ]
                ]
            ]);
    }

    /** @test */
    function we_can_get_a_form_with_a_single_select_field()
    {
        $field = factory(Field::class)->create([
            "type" => "select",
            "multiple" => false,
            "values" => ["A", "B", "C"],
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this->get("/formoj/api/form/{$field->section->form_id}")
            ->assertStatus(200)
            ->assertJsonFragment([
                "fields" => [
                    [
                        "id" => $field->id,
                        "type" => "select",
                        "label" => $field->label,
                        "helpText" => $field->description,
                        "required" => $field->required,
                        "multiple" => false,
                        "options" => [
                            ["id"=>1, "label"=>"A"],
                            ["id"=>2, "label"=>"B"],
                            ["id"=>3, "label"=>"C"],
                        ],
                    ]
                ]
            ]);
    }

    /** @test */
    function we_can_get_a_form_with_a_multiple_select_field()
    {
        $field = factory(Field::class)->create([
            "type" => "select",
            "multiple" => true,
            "max_values" => 2,
            "values" => ["A", "B", "C"],
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this->get("/formoj/api/form/{$field->section->form_id}")
            ->assertStatus(200)
            ->assertJsonFragment([
                "fields" => [
                    [
                        "id" => $field->id,
                        "type" => "select",
                        "label" => $field->label,
                        "helpText" => $field->description,
                        "required" => $field->required,
                        "multiple" => true,
                        "max" => 2,
                        "options" => [
                            ["id"=>1, "label"=>"A"],
                            ["id"=>2, "label"=>"B"],
                            ["id"=>3, "label"=>"C"],
                        ],
                    ]
                ]
            ]);
    }

    /** @test */
    function we_can_get_a_form_with_an_heading_field()
    {
        $field = factory(Field::class)->create([
            "type" => "heading",
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this->get("/formoj/api/form/{$field->section->form_id}")
            ->assertStatus(200)
            ->assertJsonFragment([
                "fields" => [
                    [
                        "id" => $field->id,
                        "type" => "heading",
                        "label" => $field->label,
                    ]
                ]
            ]);
    }
}
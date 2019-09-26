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
    function we_can_get_a_form_properly_formatted()
    {
        $field = factory(Field::class)->create([
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "is_title_hidden" => true,
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this->get("/formoj/api/form/{$field->section->form_id}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => $field->section->form_id,
                    "title" => $field->section->form->title,
                    "isTitleHidden" => true,
                    "description" => $field->section->form->description,
                    "sections" => [
                        [
                            "id" => $field->section->id,
                            "title" => $field->section->title,
                            "description" => $field->section->description,
                            "fields" => [
                                [
                                    "id" => "f" . $field->id,
                                    "type" => $field->type,
                                    "helpText" => $field->help_text,
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
            "field_attributes->max_length" => 10,
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
                        "id" => "f" . $field->id,
                        "type" => "text",
                        "label" => $field->label,
                        "helpText" => $field->help_text,
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
            "field_attributes->max_length" => 10,
            "field_attributes->rows_count" => 12,
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
                        "id" => "f" . $field->id,
                        "type" => "textarea",
                        "label" => $field->label,
                        "helpText" => $field->help_text,
                        "required" => $field->required,
                        "rows" => 12,
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
            "field_attributes->multiple" => false,
            "field_attributes->options" => ["A", "B", "C"],
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
                        "id" => "f" . $field->id,
                        "type" => "select",
                        "label" => $field->label,
                        "helpText" => $field->help_text,
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
            "field_attributes->multiple" => true,
            "field_attributes->max_options" => 2,
            "field_attributes->options" => ["A", "B", "C"],
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
                        "id" => "f" . $field->id,
                        "type" => "select",
                        "label" => $field->label,
                        "helpText" => $field->help_text,
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
                        "id" => "f" . $field->id,
                        "type" => "heading",
                        "content" => $field->label,
                    ]
                ]
            ]);
    }

    /** @test */
    function we_can_get_a_form_with_an_upload_field()
    {
        $field = factory(Field::class)->create([
            "type" => "upload",
            "field_attributes->max_size" => 4,
            "field_attributes->accept" => ".jpg,.gif",
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
                        "id" => "f" . $field->id,
                        "type" => "upload",
                        "label" => $field->label,
                        "helpText" => $field->help_text,
                        "required" => $field->required,
                        "maxSize" => 4,
                        "accept" => ".jpg,.gif",
                    ]
                ]
            ]);
    }

    /** @test */
    function we_can_not_get_a_not_published_already_form()
    {
        $form = factory(Form::class)->create([
            "published_at" => now()->addHour(),
            "unpublished_at" => null,
        ]);

        $this->getJson("/formoj/api/form/{$form->id}")
            ->assertStatus(409);
    }

    /** @test */
    function we_can_not_get_a_to_be_published_in_the_future_form()
    {
        $form = factory(Form::class)->create([
            "published_at" => null,
            "unpublished_at" => now()->subHour(),
        ]);

        $this->getJson("/formoj/api/form/{$form->id}")
            ->assertStatus(409);
    }

    /** @test */
    function we_can_get_a_form_with_valid_publish_dates()
    {
        $form = factory(Form::class)->create([
            "published_at" => now()->subHour(),
            "unpublished_at" => now()->addHour(),
        ]);

        $this->getJson("/formoj/api/form/{$form->id}")
            ->assertStatus(200);
    }
}
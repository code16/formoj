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
                                    "label" => $field->label,
                                    "required" => $field->required
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
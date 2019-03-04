<?php

namespace Code16\Formoj\Tests\Feature;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class FormojFormFillControllerTest extends FormojTestCase
{
    use RefreshDatabase;

    /** @test */
    function we_can_fill_a_form_with_one_section()
    {
        $this->withoutExceptionHandling();
        $answer = Str::random(5);

        $field = factory(Field::class)->create([
            "type" => "text",
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => $answer,
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas("formoj_answers", [
            "form_id" => $field->section->form_id,
            "content" => json_encode([
                "f" . $field->id => $answer
            ])
        ]);
    }

    /** @test */
    function we_cant_fill_an_outdated_form()
    {
        $field = factory(Field::class)->create([
            "type" => "text",
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => now()->addHour(),
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => Str::random(),
            ])
            ->assertStatus(403);

        $field = factory(Field::class)->create([
            "type" => "text",
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => now()->subHour(),
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => Str::random(),
            ])
            ->assertStatus(403);
    }

    /** @test */
    function the_last_section_of_the_form_is_validated()
    {
        $field = factory(Field::class)->create([
            "type" => "text",
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => "",
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("f" . $field->id);

        $this->assertDatabaseMissing("formoj_answers", [
            "form_id" => $field->section->form_id,
        ]);
    }

    /** @test */
    function we_store_only_the_form_data_with_the_answer()
    {
        $field = factory(Field::class)->create([
            "type" => "text",
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $field2 = factory(Field::class)->create([
            "type" => "text",
            "required" => false,
            "section_id" => $field->section_id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => "test",
                "f" . $field2->id => "test",
                "sometingelse" => "should not be stored",
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas("formoj_answers", [
            "form_id" => $field->section->form_id,
            "content" => json_encode([
                "f" . $field->id => "test",
                "f" . $field2->id => "test",
            ])
        ]);
    }
}
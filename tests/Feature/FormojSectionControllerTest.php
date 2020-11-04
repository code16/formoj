<?php

namespace Code16\Formoj\Tests\Feature;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormojSectionControllerTest extends FormojTestCase
{
    use RefreshDatabase;

    /** @test */
    function we_get_a_422_when_posting_null_for_a_required_field()
    {
        $field = Field::factory()->create([
            "type" => "text",
            "required" => true,
            "section_id" => Section::factory()->create([
                "form_id" => Form::factory()->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $field2 = Field::factory()->create([
            "type" => "text",
            "required" => false,
            "section_id" => $field->section_id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => "",
                "f" . $field2->id => "",
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("f" . $field->id)
            ->assertJsonMissingValidationErrors("f" . $field2->id);
    }

    /** @test */
    function we_get_a_422_when_posting_a_too_long_text_with_a_max_length_property()
    {
        $field = Field::factory()->create([
            "type" => "text",
            "required" => false,
            "section_id" => Section::factory()->create([
                "form_id" => Form::factory()->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $field->field_attributes = ["max_length" => 3];
        $field->save();

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => "ABCD",
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("f" . $field->id);
    }

    /** @test */
    function we_get_a_422_when_posting_a_non_existing_value_to_a_single_select()
    {
        $field = Field::factory()->create([
            "type" => "select",
            "required" => false,
            "section_id" => Section::factory()->create([
                "form_id" => Form::factory()->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $field->field_attributes = [
            "multiple" => false,
            "options" => ["one", "two"]
        ];
        $field->save();

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => 3,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("f" . $field->id);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => [2],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("f" . $field->id);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => 2,
            ])
            ->assertStatus(200);
    }

    /** @test */
    function we_get_a_422_when_posting_a_non_existing_value_to_a_multiple_select()
    {
        $field = Field::factory()->create([
            "type" => "select",
            "required" => false,
            "section_id" => Section::factory()->create([
                "form_id" => Form::factory()->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $field->field_attributes = [
            "multiple" => true,
            "options" => ["one", "two"]
        ];
        $field->save();

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => [3],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("f" . $field->id);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => [1,2,3],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("f" . $field->id);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => 3,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("f" . $field->id);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => [1,2],
            ])
            ->assertStatus(200);
    }

    /** @test */
    function we_get_a_422_when_posting_to_much_values_to_a_multiple_select_with_max_options()
    {
        $field = Field::factory()->create([
            "type" => "select",
            "required" => false,
            "section_id" => Section::factory()->create([
                "form_id" => Form::factory()->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $field->field_attributes = [
            "multiple" => true,
            "options" => ["one", "two", "three"],
            "max_options" => 2,
        ];
        $field->save();

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => [1,2,3],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("f" . $field->id);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => [1,2],
            ])
            ->assertStatus(200);
    }

    /** @test */
    function we_cant_validate_a_section_of_an_outdated_form()
    {
        $field = Field::factory()->create([
            "type" => "text",
            "section_id" => Section::factory()->create([
                "form_id" => Form::factory()->create([
                    "published_at" => now()->addHour(),
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => "test",
            ])
            ->assertStatus(403);

        $field = Field::factory()->create([
            "type" => "text",
            "section_id" => Section::factory()->create([
                "form_id" => Form::factory()->create([
                    "published_at" => null,
                    "unpublished_at" => now()->subHour(),
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "f" . $field->id => "test",
            ])
            ->assertStatus(403);
    }
}
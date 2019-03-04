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
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "data" => [
                    $field->id => "",
                    $field2->id => "",
                ]
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("data.{$field->id}")
            ->assertJsonMissingValidationErrors("data.{$field2->id}");
    }

    /** @test */
    function we_get_a_422_when_posting_a_too_long_text_with_a_max_length_property()
    {
        $field = factory(Field::class)->create([
            "type" => "text",
            "required" => false,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $field->field_attributes = ["max_length" => 3];
        $field->save();

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "data" => [
                    $field->id => "ABCD",
                ]
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("data.{$field->id}");
    }

    /** @test */
    function we_get_a_422_when_posting_a_non_exisiting_value_to_select()
    {
        $field = factory(Field::class)->create([
            "type" => "select",
            "required" => false,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
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
                "data" => [
                    $field->id => 3,
                ]
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("data.{$field->id}");

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
                "data" => [
                    $field->id => 2,
                ]
            ])
            ->assertStatus(200);
    }

//    /** @test */
//    function we_get_a_422_when_posting_multiple_values_to_a_single_select()
//    {
//        $field = factory(Field::class)->create([
//            "type" => "select",
//            "required" => false,
//            "section_id" => factory(Section::class)->create([
//                "form_id" => factory(Form::class)->create([
//                    "published_at" => null,
//                    "unpublished_at" => null,
//                ])->id
//            ])->id
//        ]);
//
//        $field->field_attributes = ["multiple" => false];
//        $field->save();
//
//        $this
//            ->postJson("/formoj/api/form/{$field->section->form_id}/validate/{$field->section_id}", [
//                "data" => [
//                    $field->id => [
//
//                    ],
//                ]
//            ])
//            ->assertStatus(422)
//            ->assertJsonValidationErrors("data.{$field->id}");
//    }
}
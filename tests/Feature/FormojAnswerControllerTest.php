<?php

namespace Code16\Formoj\Tests\Feature;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Field;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormojAnswerControllerTest extends FormojTestCase
{
    use RefreshDatabase;
    
    /** @test */
    function we_cant_get_a_non_existing_answer()
    {
        $this->get("/formoj/api/answer/1")
            ->assertStatus(404);
    }

    /** @test */
    function we_can_get_a_answer_with_fields()
    {
        $this->withoutExceptionHandling();
        
        $field = factory(Field::class)->create([
            "label" => "Field label",
            "identifier" => "field_1",
            "type" => "select",
        ]);
        
        $answer = factory(Answer::class)->create([
            'content' => [
                "field_1" => "some answer"
            ],
            'form_id' => $field->section->form_id
        ]);

        $this->get("/formoj/api/answer/{$answer->id}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "content" => [
                        "field_1" => "some answer"
                    ],
                    "fields" => [
                        [
                            "name" => "field_1",
                            "label" => "Field label",
                            "type" => "select"
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    function we_allow_missing_fields()
    {
        $this->withoutExceptionHandling();
        
        $answer = factory(Answer::class)->create([
            'content' => [
                "some_field" => "some answer" // a missing field
            ]
        ]);
        
        $this->get("/formoj/api/answer/{$answer->id}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "content" => [
                        "some_field" => "some answer"
                    ],
                    "fields" => [
                    ]
                ]
            ])
            ->assertJsonCount(0, "data.fields");
    }

    /** @test */
    function we_can_get_only_field_of_the_current_answer()
    {
        $this->withoutExceptionHandling();

        // Noise
        factory(Field::class)->create([
            "label" => "Field label",
            "identifier" => "field_1",
            "type" => "text",
        ]);

        $field = factory(Field::class)->create([
            "label" => "Field label",
            "identifier" => "field_1",
            "type" => "select",
        ]);

        $answer = factory(Answer::class)->create([
            'content' => [
                "field_1" => "some answer"
            ],
            'form_id' => $field->section->form_id
        ]);

        $this->get("/formoj/api/answer/{$answer->id}")
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "content" => [
                        "field_1" => "some answer"
                    ],
                    "fields" => [
                        [
                            "name" => "field_1",
                            "label" => "Field label",
                            "type" => "select"
                        ]
                    ]
                ]
            ]);
    }
}

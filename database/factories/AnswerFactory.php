<?php

namespace Database\Factories;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => [
                "field 1" => $this->faker->sentence
            ],
            'form_id' => function() {
                return Form::factory()->create()->id;
            }
        ];
    }
}
<?php

namespace Database\Factories;

use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Section::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
            'is_title_hidden' => $this->faker->boolean(),
            'description' => $this->faker->paragraph,
            'form_id' => function() {
                return Form::factory()->create()->id;
            }
        ];
    }
}
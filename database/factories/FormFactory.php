<?php

namespace Code16\Formoj\Database\Factories;

use Code16\Formoj\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Form::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $hasPublishedDate = $this->faker->boolean();
        $publishedDate = $hasPublishedDate ? $this->faker->dateTimeBetween('-10 days', '+5 days') : null;
        $unpublishedDate = $this->faker->boolean()
            ? ($hasPublishedDate ? $this->faker->dateTimeBetween($publishedDate, '+15 days') : $this->faker->dateTimeBetween('-10 days', '+5 days'))
            : null;

        return [
            'title' => $this->faker->words(2, true),
            'is_title_hidden' => $this->faker->boolean(),
            'description' => $this->faker->paragraph,
            'published_at' => $publishedDate,
            'unpublished_at' => $unpublishedDate,
        ];
    }
}

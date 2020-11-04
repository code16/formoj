<?php

namespace Database\Factories;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class FieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Field::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $attributes["type"] ?? $this->faker->randomElement([
                \Code16\Formoj\Models\Field::TYPE_TEXT,
                \Code16\Formoj\Models\Field::TYPE_TEXTAREA,
                \Code16\Formoj\Models\Field::TYPE_SELECT,
                \Code16\Formoj\Models\Field::TYPE_UPLOAD,
            ]);

        $fieldAttributes = [];

        if($type == \Code16\Formoj\Models\Field::TYPE_SELECT) {
            for($i=0; $i<rand(3, 12); $i++) {
                $fieldAttributes["options"][] = $this->faker->unique()->word;
            }
            if($this->faker->boolean(40)) {
                $fieldAttributes["multiple"] = true;
                $fieldAttributes["max_options"] = $this->faker->boolean() ? $this->faker->numberBetween(2, 4) : null;
            }
        } elseif($type == \Code16\Formoj\Models\Field::TYPE_UPLOAD) {
            $fieldAttributes["max_size"] = 4;
            $fieldAttributes["accept"] = ".jpeg,.jpg,.gif,.png,.pdf";
        }

        $label = $this->faker->words(3, true);

        return [
            'label' => $label,
            'identifier' => Str::slug($label,'_'),
            'help_text' => $this->faker->boolean(25) ? $this->faker->paragraph : null,
            'type' => $type,
            'field_attributes' => $fieldAttributes,
            'required' => $type == \Code16\Formoj\Models\Field::TYPE_HEADING ? false : $this->faker->boolean(40),
            'section_id' => function() {
                return Section::factory()->create()->id;
            }
        ];
    }
}
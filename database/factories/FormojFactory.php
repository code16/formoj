<?php

use Faker\Generator as Faker;

$factory->define(\Code16\Formoj\Models\Form::class, function (Faker $faker) {
    $hasPublishedDate = $faker->boolean();
    $publishedDate = $hasPublishedDate ? $faker->dateTimeBetween('-10 days', '+5 days') : null;
    $unpublishedDate = $faker->boolean()
        ? ($hasPublishedDate ? $faker->dateTimeBetween($publishedDate, '+15 days') : $faker->dateTimeBetween('-10 days', '+5 days'))
        : null;

    return [
        'title' => $faker->words(2, true),
        'description' => $faker->paragraph,
        'published_at' => $publishedDate,
        'unpublished_at' => $unpublishedDate,
    ];
});

$factory->define(\Code16\Formoj\Models\Section::class, function (Faker $faker) {
    return [
        'title' => $faker->words(3, true),
        'description' => $faker->paragraph,
        'form_id' => function() {
            return factory(\Code16\Formoj\Models\Form::class)->create()->id;
        }
    ];
});

$factory->define(\Code16\Formoj\Models\Field::class, function (Faker $faker, $attributes) {
    $type = $attributes["type"] ?? $faker->randomElement([
        \Code16\Formoj\Models\Field::TYPE_TEXT,
        \Code16\Formoj\Models\Field::TYPE_TEXTAREA,
        \Code16\Formoj\Models\Field::TYPE_SELECT,
    ]);

    $values = null;
    $maxValues = null;
    $multiple = false;
    if($type == \Code16\Formoj\Models\Field::TYPE_SELECT) {
        for($i=0; $i<rand(3, 12); $i++) {
            $values[] = $faker->unique()->word;
        }
        if($multiple = $faker->boolean(40)) {
            $maxValues = $faker->boolean() ? $faker->numberBetween(2, 4) : null;
        }
    }

    return [
        'label' => $faker->words(3, true),
        'description' => $faker->boolean(25) ? $faker->paragraph : null,
        'required' => $faker->boolean(40),
        'type' => $type,
        'max_length' => in_array($type, [\Code16\Formoj\Models\Field::TYPE_TEXT, \Code16\Formoj\Models\Field::TYPE_TEXTAREA])
            ? ($faker->boolean ? $faker->randomNumber(2) : null)
            : null,
        'values' => $values,
        'max_values' => $maxValues,
        'multiple' => $multiple,
        'section_id' => function() {
            return factory(\Code16\Formoj\Models\Section::class)->create()->id;
        }
    ];
});
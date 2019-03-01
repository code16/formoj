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

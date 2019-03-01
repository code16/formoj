<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            "email" => "admin@example.com",
        ]);

        foreach(factory(\Code16\Formoj\Models\Form::class, 5)->create() as $form) {
            for($k=1; $k<=rand(1, 4); $k++) {
                $section = factory(\Code16\Formoj\Models\Section::class)->create([
                    "title" => "Section $k",
                    "form_id" => $form->id
                ]);

                factory(\Code16\Formoj\Models\Field::class, rand(3, 8))->create([
                    "section_id" => $section->id
                ]);
            }
        }
    }
}

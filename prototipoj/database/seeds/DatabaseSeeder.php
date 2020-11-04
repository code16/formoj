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
        \App\User::factory()->create([
            "email" => "admin@example.com",
        ]);

        foreach(\Code16\Formoj\Models\Form::factory()->count(5)->create() as $form) {
            for($k=1; $k<=rand(1, 4); $k++) {
                $section = \Code16\Formoj\Models\Section::factory()->create([
                    "title" => "Section $k",
                    "form_id" => $form->id
                ]);

                if(rand(0, 9) >= 6) {
                    \Code16\Formoj\Models\Field::factory()->count(rand(1, 3))->create([
                        "section_id" => $section->id
                    ]);

                    \Code16\Formoj\Models\Field::factory()->create([
                        "type" => "heading",
                        "required" => false,
                        "help_text" => null,
                        "section_id" => $section->id
                    ]);
                }

                \Code16\Formoj\Models\Field::factory()->count(rand(3, 8))->create([
                    "section_id" => $section->id
                ]);
            }
        }
    }
}

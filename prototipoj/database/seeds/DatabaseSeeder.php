<?php

use App\User;
use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;
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
        User::factory()->create([
            "email" => "admin@example.org",
        ]);

        foreach(Form::factory()->count(5)->create() as $form) {
            for($k=1; $k<=rand(1, 4); $k++) {
                $section = Section::factory()->create([
                    "title" => "Section $k",
                    "form_id" => $form->id
                ]);

                if(rand(0, 9) >= 6) {
                    Field::factory()
                        ->count(rand(1, 3))
                        ->create([
                            "section_id" => $section->id
                        ]);

                    Field::factory()->create([
                        "type" => "heading",
                        "required" => false,
                        "help_text" => null,
                        "section_id" => $section->id
                    ]);
                }

                Field::factory()
                    ->count(rand(3, 8))
                    ->create([
                        "section_id" => $section->id
                    ]);
            }
        }
    }
}

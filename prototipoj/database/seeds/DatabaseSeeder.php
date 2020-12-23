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
        factory(User::class)->create([
            "email" => "admin@example.org",
        ]);

        foreach(factory(Form::class, 5)->create() as $form) {
            for($k=1; $k<=rand(1, 4); $k++) {
                $section = factory(Section::class)->create([
                    "title" => "Section $k",
                    "form_id" => $form->id
                ]);

                if(rand(0, 9) >= 6) {
                    factory(Field::class, rand(1, 3))->create([
                        "section_id" => $section->id
                    ]);

                    factory(Field::class)->create([
                        "type" => "heading",
                        "required" => false,
                        "help_text" => null,
                        "section_id" => $section->id
                    ]);
                }

                factory(Field::class, rand(3, 8))->create([
                    "section_id" => $section->id
                ]);
            }
        }
    }
}

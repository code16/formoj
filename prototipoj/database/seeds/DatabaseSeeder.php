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

        $forms = factory(\Code16\Formoj\Models\Form::class, 5)->create();
        foreach($forms as $form) {
            for($k=1; $k<=rand(1, 4); $k++) {
                factory(\Code16\Formoj\Models\Section::class)->create([
                    "title" => "Section $k",
                    "form_id" => $form->id
                ]);
            }
        }
    }
}

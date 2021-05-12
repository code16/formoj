<?php

namespace Code16\Formoj\Tests\Feature;

use Carbon\Carbon;
use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Notifications\FormojFormWasJustAnswered;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FormojFormFillControllerTest extends FormojTestCase
{
    use RefreshDatabase;

    /** @test */
    function we_can_fill_a_form_with_one_section()
    {
        $this->withoutNotifications();
        $answer = Str::random(5);

        $field = factory(Field::class)->create([
            "type" => "text",
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                    "success_message" => "OK!"
                ])->id
            ])->id
        ]);

        $response = $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => $answer,
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas("formoj_answers", [
            "form_id" => $field->section->form_id,
            "content" => json_encode([
                $field->identifier => $answer
            ])
        ]);
        
        $answer = Answer::where("form_id", $field->section->form_id)->first();
        
        $response->assertJson([
            "answer_id" => $answer->id,
            "message" => "OK!"
        ]);
    }

    /** @test */
    function we_cant_fill_an_outdated_form()
    {
        $this->withoutNotifications();

        $field = factory(Field::class)->create([
            "type" => "text",
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => now()->addHour(),
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => Str::random(),
            ])
            ->assertStatus(403);

        $field = factory(Field::class)->create([
            "type" => "text",
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => now()->subHour(),
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => Str::random(),
            ])
            ->assertStatus(403);
    }

    /** @test */
    function the_last_section_of_the_form_is_validated()
    {
        $this->withoutNotifications();

        $field = factory(Field::class)->create([
            "type" => "text",
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => "",
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors("f" . $field->id);

        $this->assertDatabaseMissing("formoj_answers", [
            "form_id" => $field->section->form_id,
        ]);
    }

    /** @test */
    function we_store_only_the_form_data_with_the_answer()
    {
        $this->withoutExceptionHandling();
        $this->withoutNotifications();

        $field = factory(Field::class)->create([
            "type" => "text",
            "field_attributes->max_length" => null,
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $field2 = factory(Field::class)->create([
            "type" => "text",
            "required" => false,
            "field_attributes->max_length" => null,
            "section_id" => $field->section_id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => "test",
                "f" . $field2->id => "test",
                "sometingelse" => "should not be stored",
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas("formoj_answers", [
            "form_id" => $field->section->form_id,
            "content" => json_encode([
                $field->identifier => "test",
                $field2->identifier => "test",
            ])
        ]);
    }

    /** @test */
    function we_dont_store_headings_with_the_answer()
    {
        $this->withoutNotifications();

        $field = factory(Field::class)->create([
            "type" => "text",
            "required" => true,
            "field_attributes->max_length" => null,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $field2 = factory(Field::class)->create([
            "type" => "heading",
            "section_id" => $field->section_id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => "test",
                "f" . $field2->id => "should not be stored",
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas("formoj_answers", [
            "form_id" => $field->section->form_id,
            "content" => json_encode([
                $field->identifier => "test"
            ])
        ]);
    }

    /** @test */
    function we_store_select_values_with_the_answer()
    {
        $this->withoutNotifications();

        $field = factory(Field::class)->create([
            "type" => "select",
            "field_attributes->multiple" => false,
            "field_attributes->options" => ["A", "B", "C"],
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => 2,
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas("formoj_answers", [
            "form_id" => $field->section->form_id,
            "content" => json_encode([
                $field->identifier => "B"
            ])
        ]);
    }

    /** @test */
    function we_store_multiple_select_values_with_the_answer()
    {
        $this->withoutNotifications();

        $field = factory(Field::class)->create([
            "type" => "select",
            "field_attributes->multiple" => true,
            "field_attributes->max_options" => 3,
            "field_attributes->options" => ["A", "B", "C"],
            "required" => true,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => [1,2],
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas("formoj_answers", [
            "form_id" => $field->section->form_id,
            "content" => json_encode([
                $field->identifier => ["A", "B"]
            ])
        ]);
    }

    /** @test */
    function we_move_uploads_and_store_filename_with_the_answer()
    {
        $this->withoutExceptionHandling();
        Storage::fake('local');

        $this->withoutNotifications();

        $field = factory(Field::class)->create([
            "type" => Field::TYPE_UPLOAD,
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        // Simulate previous answers
        factory(Answer::class, 20)->create([
            "form_id" => $field->section->form_id
        ]);

        Carbon::setTestNow(Carbon::now()->addSecond());

        // Simulate a previous upload
        UploadedFile::fake()->image('image.jpg')->storeAs("formoj/tmp/{$field->section->form_id}", "image.jpg", "local");

        $this
            ->postJson("/formoj/api/form/{$field->section->form_id}", [
                "f" . $field->id => ["file" => "image.jpg"],
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas("formoj_answers", [
            "form_id" => $field->section->form_id,
            "content" => json_encode([
                $field->identifier => "image.jpg"
            ])
        ]);

        $answer = Answer::latest()->first();

        Storage::disk('local')
            ->assertExists("formoj/forms/{$field->section->form_id}/answers/{$answer->id}/image.jpg");
    }

    /** @test */
    function posting_a_new_answer_sends_a_notification_if_configured()
    {
        Notification::fake();

        $field = factory(Field::class)->create([
            "type" => "text",
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "notifications_strategy" => Form::NOTIFICATION_STRATEGY_EVERY,
                    "administrator_email" => "admin@example.org",
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this->postJson("/formoj/api/form/{$field->section->form_id}", [
            "f" . $field->id => "test",
        ]);

        Notification::assertSentTo(
            new AnonymousNotifiable,
            FormojFormWasJustAnswered::class,
            function($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] == "admin@example.org";
            }
        );
    }

    /** @test */
    function posting_a_new_answer_does_not_sends_a_notification_if_not_configured()
    {
        Notification::fake();

        $field = factory(Field::class)->create([
            "type" => "text",
            "section_id" => factory(Section::class)->create([
                "form_id" => factory(Form::class)->create([
                    "notifications_strategy" => Form::NOTIFICATION_STRATEGY_NONE,
                    "administrator_email" => "admin@example.org",
                    "published_at" => null,
                    "unpublished_at" => null,
                ])->id
            ])->id
        ]);

        $this->postJson("/formoj/api/form/{$field->section->form_id}", [
            "f" . $field->id => "test",
        ]);

        Notification::assertNotSentTo(new AnonymousNotifiable, FormojFormWasJustAnswered::class);
    }

    /** @test */
    function all_sections_of_the_form_are_validated_if_validate_all_argument_is_passed()
    {
        $this->withoutNotifications();
        
        $form = factory(Form::class)
            ->create([
                "published_at" => null,
                "unpublished_at" => null,
            ]);

        $field1 = factory(Field::class)->create([
            "type" => "text",
            "required" => true,
            "section_id" => factory(Section::class)
                ->create([
                    "form_id" => $form->id
                ])
                ->id
        ]);

        $field2 = factory(Field::class)->create([
            "type" => "text",
            "required" => true,
            "section_id" => factory(Section::class)
                ->create([
                    "form_id" => $form->id
                ])
                ->id
        ]);

        $this
            ->postJson("/formoj/api/form/{$form->id}?validate_all=1", [
                "f{$field1->id}" => "",
                "f{$field2->id}" => "",
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(["f{$field1->id}", "f{$field2->id}"]);
    }
}
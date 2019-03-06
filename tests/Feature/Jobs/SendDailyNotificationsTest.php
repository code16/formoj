<?php

namespace Code16\Formoj\Tests\Feature\Jobs;

use Code16\Formoj\Job\SendDailyNotifications;
use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Notifications\FormojFormWasAnsweredToday;
use Code16\Formoj\Tests\FormojTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class SendDailyNotificationsTest extends FormojTestCase
{
    use RefreshDatabase;

    /** @test */
    function we_send_a_grouped_notification_if_configured()
    {
        Notification::fake();

        $answers = factory(Answer::class, 4)->create([
            "form_id" => factory(Form::class)->create([
                "notifications_strategy" => Form::NOTIFICATION_STRATEGY_GROUPED,
                "administrator_email" => "admin@example.org"
            ])
        ]);

        // Outdate one of them
        $outDatedAnswer = Answer::first();
        $outDatedAnswer->created_at = Carbon::yesterday();
        $outDatedAnswer->save(['timestamps' => false]);

        // Create answer for various forms without NOTIFICATION_STRATEGY_GROUPED
        factory(Answer::class, 10)->create([
            "form_id" => factory(Form::class)->create([
                "notifications_strategy" => Form::NOTIFICATION_STRATEGY_EVERY,
                "administrator_email" => "admin2@example.org"
            ])
        ]);

        app(SendDailyNotifications::class)->handle(today());

        Notification::assertSentTo(
            new AnonymousNotifiable,
            FormojFormWasAnsweredToday::class,
            function($notification, $channels, $notifiable) use($answers) {
                return
                    $notification->form->id == $answers->first()->form_id
                    && count($notification->answers) == 3
                    && $notifiable->routes['mail'] == "admin@example.org";
            }
        );

        Notification::assertTimesSent(1, FormojFormWasAnsweredToday::class);
    }
}
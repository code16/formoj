<?php

namespace Code16\Formoj\Job;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Notifications\FormojFormWasAnsweredToday;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class SendDailyNotifications
{
    use Dispatchable;

    /**
     * @param Carbon $day
     */
    public function handle(Carbon $day)
    {
        Answer::whereDate("created_at", $day->format('Y-m-d'))
            ->orderBy("created_at", "asc")
            ->whereIn("form_id", function($query) {
                return $query->from("formoj_forms")
                    ->select("id")
                    ->where("notifications_strategy", Form::NOTIFICATION_STRATEGY_GROUPED);
            })
            ->get()
            ->groupBy("form_id")
            ->each(function($answers, $formId) {
                $form = Form::findOrFail($formId);

                Notification::route('mail', $form->administrator_email)
                    ->notify(new FormojFormWasAnsweredToday($form, $answers));
            });
    }
}
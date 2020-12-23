<?php

namespace Code16\Formoj\Notifications;

use Code16\Formoj\Models\Form;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormojFormWasAnsweredToday extends Notification implements ShouldQueue
{
    use Queueable;

    public Form $form;
    public Collection $answers;

    public function __construct(Form $form, Collection $answers)
    {
        $this->form = $form;
        $this->answers = $answers;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject(
                trans('formoj::form.notifications.daily_answers.subject', [
                    'form' => $this->form->getLabel()
                ])
            )
            ->greeting(trans('formoj::form.notifications.daily_answers.greeting', [
                'count' => count($this->answers)
            ]));

        foreach($this->answers->take(3) as $answer) {
            foreach ($answer->content as $field => $value) {
                if (is_array($value)) {
                    $value = implode(", ", $value);
                }

                $message->line($field . ": " . $value);
            }

            $message->line("---");
        }

        if(count($this->answers) > 3) {
            $message->line("...");
        }

        return $message;
    }
}
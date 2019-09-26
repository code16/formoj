<?php

namespace Code16\Formoj\Notifications;

use Code16\Formoj\Models\Answer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormojFormWasJustAnswered extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var Answer */
    protected $answer;

    /**
     * @param Answer $answer
     */
    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject(
                trans('formoj::form.notifications.new_answer.subject', [
                    'form' => $this->answer->form->getLabel()
                ])
            )
            ->greeting(trans('formoj::form.notifications.new_answer.greeting', [
                'date' => $this->answer->created_at->isoFormat("LLLL")
            ]));

        foreach($this->answer->content as $field => $value) {
            if (is_array($value)) {
                $value = implode(", ", $value);
            }

            $message->line($field . ": " . $value);
        }

        return $message;
    }
}
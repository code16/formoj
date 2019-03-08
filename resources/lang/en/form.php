<?php

return [

    'success_message' => 'Thank you, your answer has been registered',
    'form_too_soon' => "This form isn't available yet.",
    'form_too_late' => "This form is no longer available.",

    'notifications' => [
        'new_answer' => [
            'subject' => 'New answer to the :form form',
            'greeting' => 'Answer of :date',
        ],
        'daily_answers' => [
            'subject' => 'Answers of the day of the form :form',
            'greeting' => ':count answer(s)',
        ]
    ]
];

<?php

return [

    'success_message' => 'Obrigado, sua resposta foi registrada',
    'form_too_soon' => "Este formulário ainda não está disponível.",
    'form_too_late' => "Este formulário já não está mais disponível.",

    'notifications' => [
        'new_answer' => [
            'subject' => 'Nova resposta para o formulário :form',
            'greeting' => 'Resposta de :date',
        ],
        'daily_answers' => [
            'subject' => 'Respostas do dia, do formulário :form',
            'greeting' => ':count resposta(s)',
        ]
    ]
];

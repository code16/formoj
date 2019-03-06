<?php

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;

return [

    'forms' => [
        'no_title' => "(sans titre)",
        'notification_strategies' => [
            Form::NOTIFICATION_STRATEGY_EVERY => "A chaque réponse",
            Form::NOTIFICATION_STRATEGY_GROUPED => "Une fois par jour",
            Form::NOTIFICATION_STRATEGY_NONE => "Jamais",
        ],
        'fields' => [
            "title" => [
                "label" => "Titre"
            ],
            "description" => [
                "label" => "Description"
            ],
            "published_at" => [
                "label" => "Du"
            ],
            "unpublished_at" => [
                "label" => "au"
            ],
            "sections" => [
                "label" => "Sections",
                "add_label" => "Ajouter une section",
                "fields" => [
                    "title" => [
                        "label" => "Titre"
                    ],
                    "description" => [
                        "label" => "Description"
                    ]
                ]
            ],
            "success_message" => [
                "label" => "Message affiché en fin de saisie du formulaire",
                "help_text" => "Ce texte sera affiché à l'utilisateur au moment de la validation de sa réponse. S'il est laissé vide, un message standard le remplacera.",
            ],
            "notifications_strategy" => [
                "label" => "Périodicité d'envoi"
            ],
            "administrator_email" => [
                "label" => "Adresse email de réception"
            ],
            "fieldsets" => [
                "dates" => "Dates de publication (facultatives)",
                "notifications" => "Notifications",
            ],
        ],
        "list" => [
            "columns" => [
                "ref_label" => "Ref",
                "title_label" => "Titre",
                "description_label" => "Description",
                "published_at_label" => "Dates publication",
                "sections_label" => "Sections",
            ],
            "data" => [
                "dates" => [
                    "both" => "Du %s<br>au %s",
                    "from" => "À partir du %s",
                    "to" => "Jusqu'au %s",
                ]
            ]
        ]
    ],

    'fields' => [
        'types' => [
            Field::TYPE_TEXT => "Texte simple",
            Field::TYPE_TEXTAREA => "Texte multilignes",
            Field::TYPE_SELECT => "Liste déroulante",
            Field::TYPE_HEADING => "Intertitre",
        ],
        'fields' => [
            "label" => [
                "label" => "Libellé"
            ],
            "type" => [
                "label" => ""
            ],
            "help_text" => [
                "label" => "Texte d'aide"
            ],
            "required" => [
                "text" => "Saisie obligatoire"
            ],
            "max_length" => [
                "label" => "Longueur maximale",
                "help_text" => "En nombre de caractères",
            ],
            "rows_count" => [
                "label" => "Nombre de lignes",
            ],
            "multiple" => [
                "text" => "Autoriser plusieurs réponses"
            ],
            "max_options" => [
                "label" => "Nombre maximum de réponses"
            ],
            "options" => [
                "label" => "Valeurs possibles",
                "add_label" => "Ajouter une valeur",
            ]
        ],
        "list" => [
            "columns" => [
                "type_label" => "",
                "label_label" => "Libellé",
                "help_text_label" => "Texte d'aide",
            ],
            "data" => [
                "label" => [
                    "required" => "obligatoire"
                ]
            ]
        ]
    ],

    'answers' => [
        "list" => [
            "columns" => [
                "created_at_label" => "Date",
                "content_label" => "",
            ],
            "data" => [
                "label" => [
                    "required" => "obligatoire"
                ]
            ]
        ],
        'commands' => [
            'view' => "Visualiser cette réponse",
            'export' => "Exporter les réponses au format XLS",
        ]
    ]

];

<?php

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;

return [
    
    'entities' => [
        'form' => "Form",
        'section' => "Section",
        'field' => "Field",
        'answer' => "Answer",
    ],

    'forms' => [
        'no_title' => "(without title)",
        'notification_strategies' => [
            Form::NOTIFICATION_STRATEGY_EVERY => "On every answer",
            Form::NOTIFICATION_STRATEGY_GROUPED => "Once a day",
            Form::NOTIFICATION_STRATEGY_NONE => "Never",
        ],
        'fields' => [
            "title" => [
                "label" => "Title"
            ],
            "is_title_hidden" => [
                "label" => "Hide form title"
            ],
            "description" => [
                "label" => "Description"
            ],
            "published_at" => [
                "label" => "From"
            ],
            "unpublished_at" => [
                "label" => "to"
            ],
            "success_message" => [
                "label" => "Message displayed after posting the form",
                "help_text" => "This text will be shown to the user when posting his answer. If let blank, a standard message will be used.",
            ],
            "notifications_strategy" => [
                "label" => "Sending frequency"
            ],
            "administrator_email" => [
                "label" => "Recipient e-mail address"
            ],
            "fieldsets" => [
                "title" => "Form title",
                "dates" => "Publication dates (optional)",
                "notifications" => "Notifications",
            ],
        ],
        "list" => [
            "columns" => [
                "ref_label" => "Ref",
                "title_label" => "Title",
                "description_label" => "Description",
                "published_at_label" => "publication dates",
                "sections_label" => "Sections",
                "answers_label" => "Answers",
            ],
            "data" => [
                "dates" => [
                    "both" => "From %s<br>to %s",
                    "from" => "From %s",
                    "to" => "Until %s",
                ]
            ]
        ]
    ],

    'sections' => [
        "list" => [
            "columns" => [
                "title_label" => "Title",
                "description_label" => "Description",
            ],
            "data" => [
                "title" => [
                    "is_hidden" => "Hidden",
                ]
            ]
        ],
        "fields" => [
            "title" => [
                "label" => "Title"
            ],
            "is_title_hidden" => [
                "label" => "Hide title"
            ],
            "description" => [
                "label" => "Description"
            ],
            "fields" => [
                "label" => "Fields"
            ]
        ],
    ],

    'fields' => [
        'types' => [
            Field::TYPE_TEXT => "Simple text",
            Field::TYPE_TEXTAREA => "Multi-rows text",
            Field::TYPE_SELECT => "Dropdown list",
            Field::TYPE_HEADING => "Inter-title",
            Field::TYPE_UPLOAD => "File",
            Field::TYPE_RATING => "Rating",
        ],
        'fields' => [
            "label" => [
                "label" => "Label"
            ],
            "identifier" => [
                "label" => "Unique identifier",
                "help_text" => "Technical field, must be unique for the whole form (not displayed to user). Please use _ separator (exemple: other_reason_1)"
            ],
            "type" => [
                "label" => ""
            ],
            "help_text" => [
                "label" => "Help text"
            ],
            "required" => [
                "text" => "Required field"
            ],
            "max_length" => [
                "label" => "Maximum length",
                "help_text" => "In number of chars",
            ],
            "rows_count" => [
                "label" => "Lines number",
            ],
            "multiple" => [
                "text" => "Allow multiple choices"
            ],
            "radios" => [
                "text" => "Display as radio buttons (can't be multiple)"
            ],
            "max_options" => [
                "label" => "Maximum number of choices"
            ],
            "options" => [
                "label" => "Possible values",
                "add_label" => "Add a value",
            ],
            "max_size" => [
                "label" => "Max size",
                "help_text" => "Integer, expressed in MB.",
            ],
            "accept" => [
                "label" => "Allowed file extensions (optional)",
                "help_text" => "Extensions list separated by commas, without space.",
            ],
            "fieldsets" => [
                "identifiers" => "Identifiers",
            ],
            "lowest_label" => [
                "label" => "Lowest rating label"
            ],
            "highest_label" => [
                "label" => "Highest rating label"
            ],
        ],
        "list" => [
            "columns" => [
                "type_label" => "",
                "label_label" => "Label",
                "help_text_label" => "Help text",
            ],
            "data" => [
                "label" => [
                    "required" => "required"
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
                    "required" => "required"
                ]
            ]
        ],
        'fields' => [
            'replies' => [
                'label' => 'Content'
            ]
        ],
        'commands' => [
            'export' => "Export answers (XLS)",
            'download_files' => "Download answer attachments",
        ],
        'errors' => [
            'no_file_to_download' => "This answer does not contains any File attachment.",
        ]
    ],

    'replies' => [
        "list" => [
            "columns" => [
                "label_label" => "Field",
                "value_label" => "Value",
            ],
        ],
    ]

];

<?php

return [

    "name" => "Prototipoj",

    "display_breadcrumb" => true,
    "locale" => env("SHARP_LOCALE", "fr_FR.UTF-8"),

    "entities" => [
        "formoj_form" => [
            "label" => "Form",
            "list" => \Code16\Formoj\Sharp\FormojFormSharpEntityList::class,
            "show" => \Code16\Formoj\Sharp\FormojFormSharpShow::class,
            "form" => \Code16\Formoj\Sharp\FormojFormSharpForm::class,
            "validator" => \Code16\Formoj\Sharp\FormojFormSharpValidator::class,
        ],
        "formoj_section" => [
            "label" => "Section",
            "list" => \Code16\Formoj\Sharp\FormojSectionSharpEntityList::class,
            "form" => \Code16\Formoj\Sharp\FormojSectionSharpForm::class,
            "show" => \Code16\Formoj\Sharp\FormojSectionSharpShow::class,
            "validator" => \Code16\Formoj\Sharp\FormojSectionSharpValidator::class,
        ],
        "formoj_field" => [
            "label" => "Field",
            "list" => \Code16\Formoj\Sharp\FormojFieldSharpEntityList::class,
            "form" => \Code16\Formoj\Sharp\FormojFieldSharpForm::class,
            "validator" => \Code16\Formoj\Sharp\FormojFieldSharpValidator::class,
        ],
        "formoj_answer" => [
            "label" => "Answer",
            "list" => \Code16\Formoj\Sharp\FormojAnswerSharpEntityList::class,
            "show" => \Code16\Formoj\Sharp\FormojAnswerSharpShow::class,
            "policy" => \Code16\Formoj\Sharp\Policies\FormojAnswerSharpPolicy::class,
        ],
        "formoj_reply" => [
            "list" => \Code16\Formoj\Sharp\FormojReplySharpEntityList::class,
            "policy" => \Code16\Formoj\Sharp\Policies\FormojReplySharpPolicy::class,
        ],
    ],

    "menu" => [
        [
            "entity" => "formoj_form",
            "label" => "Formulaires",
            "icon" => "fa-list-alt"
        ],
    ],

    "uploads" => [
        "tmp_dir" => env("SHARP_UPLOADS_TMP_DIR", "tmp"),
        "thumbnails_dir" => env("SHARP_UPLOADS_THUMBS_DIR", "thumbnails"),
    ],

    "auth" => [
        "login_attribute" => "email",
        "password_attribute" => "password",
        "display_attribute" => "email",
    ]
];

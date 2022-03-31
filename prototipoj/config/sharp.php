<?php

return [

    "name" => "Prototipoj",

    "display_breadcrumb" => true,
    "locale" => env("SHARP_LOCALE", "fr_FR.UTF-8"),

    "entities" => [
        "formoj_form" => \Code16\Formoj\Sharp\Entities\FormojFormEntity::class,
        "formoj_section" => \Code16\Formoj\Sharp\Entities\FormojSectionEntity::class,
        "formoj_field" => \Code16\Formoj\Sharp\Entities\FormojFieldEntity::class,
        "formoj_answer" => \Code16\Formoj\Sharp\Entities\FormojAnswerEntity::class,
        "formoj_reply" => \Code16\Formoj\Sharp\Entities\FormojReplyEntity::class,
    ],

    "menu" => [
        [
            "entity" => "formoj_form",
            "label" => "Forms",
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

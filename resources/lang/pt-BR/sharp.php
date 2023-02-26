<?php

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;

return [

    'entities' => [
        'form' => "Formulário",
        'section' => "Seção",
        'field' => "Campo",
        'answer' => "Resposta",
    ],

    'forms' => [
        'no_title' => "(sem título)",
        'notification_strategies' => [
            Form::NOTIFICATION_STRATEGY_EVERY => "A cada resposta",
            Form::NOTIFICATION_STRATEGY_GROUPED => "Uma vez por dia",
            Form::NOTIFICATION_STRATEGY_NONE => "Nunca",
        ],
        'fields' => [
            "title" => [
                "label" => "Título"
            ],
            "is_title_hidden" => [
                "label" => "Não mostrar o título"
            ],
            "description" => [
                "label" => "Descrição"
            ],
            "published_at" => [
                "label" => "De"
            ],
            "unpublished_at" => [
                "label" => "até"
            ],
            "success_message" => [
                "label" => "Mensagem mostrada após responder o formulário",
                "help_text" => "Este texto será mostrado ao usuário quando ele submeter sua resposta. Caso deixada em branco, uma mensagem padrão será usada.",
            ],
            "notifications_strategy" => [
                "label" => "Frequência de envio ao administrador"
            ],
            "administrator_email" => [
                "label" => "E-mail do administrador"
            ],
            "fieldsets" => [
                "title" => "Título do formulário",
                "dates" => "Data de disponibilização (opcional)",
                "notifications" => "Notificações",
            ],
        ],
        "list" => [
            "columns" => [
                "ref_label" => "ID",
                "title_label" => "Título",
                "description_label" => "Descrição",
                "published_at_label" => "datas de disponibilidade",
                "sections_label" => "Seções",
                "answers_label" => "Respostas",
            ],
            "data" => [
                "dates" => [
                    "both" => "De %s<br>até %s",
                    "from" => "De %s",
                    "to" => "Até %s",
                ]
            ]
        ]
    ],

    'sections' => [
        "list" => [
            "columns" => [
                "title_label" => "Título",
                "description_label" => "Descrição",
            ],
            "data" => [
                "title" => [
                    "is_hidden" => "Escondido",
                ]
            ]
        ],
        "fields" => [
            "title" => [
                "label" => "Título"
            ],
            "is_title_hidden" => [
                "label" => "Título escondido"
            ],
            "description" => [
                "label" => "Descrição"
            ],
            "fields" => [
                "label" => "Campos"
            ]
        ],
    ],

    'fields' => [
        'types' => [
            Field::TYPE_TEXT => "Texto simples",
            Field::TYPE_TEXTAREA => "Texto com várias linhas",
            Field::TYPE_SELECT => "Lista de seleção (dropdown)",
            Field::TYPE_HEADING => "Subtítulo",
            Field::TYPE_UPLOAD => "Arquivo",
        ],
        'fields' => [
            "label" => [
                "label" => "Pergunta"
            ],
            "identifier" => [
                "label" => "Identificador único",
                "help_text" => "Campo técnico, precisa ser único para todo o formulário (não é mostrado ao usuário). Utilize o separador _  (exemplo: time_do_coracao)"
            ],
            "type" => [
                "label" => ""
            ],
            "help_text" => [
                "label" => "Texto de ajuda"
            ],
            "required" => [
                "text" => "Campo obrigatório"
            ],
            "max_length" => [
                "label" => "Tamanho máximo",
                "help_text" => "Em número de caracteres",
            ],
            "rows_count" => [
                "label" => "Número de linhas",
            ],
            "multiple" => [
                "text" => "Permitir múltipla escolha"
            ],
            "radios" => [
                "text" => "Mostrar como botões radio"
            ],
            "max_options" => [
                "label" => "Número máximo de escolhas"
            ],
            "options" => [
                "label" => "Valores possíveis",
                "add_label" => "Adicionar um valor",
            ],
            "max_size" => [
                "label" => "Tamanho máximo",
                "help_text" => "Inteiro, em MegaBytes.",
            ],
            "accept" => [
                "label" => "Extensões permitidas (opcional)",
                "help_text" => "Lista de extensões separadas por vírgula, sem espaços.",
            ],
            "fieldsets" => [
                "identifiers" => "Identificadores",
            ],
        ],
        "list" => [
            "columns" => [
                "type_label" => "",
                "label_label" => "Pergunta",
                "help_text_label" => "Texto de ajuda",
            ],
            "data" => [
                "label" => [
                    "required" => "obrigatório"
                ]
            ]
        ]
    ],

    'answers' => [
        "list" => [
            "columns" => [
                "created_at_label" => "Data",
                "content_label" => "",
            ],
            "data" => [
                "label" => [
                    "required" => "obrigatório"
                ]
            ]
        ],
        'fields' => [
            'replies' => [
                'label' => 'Conteúdo'
            ]
        ],
        'commands' => [
            'export' => "Exportar respostas (XLS)",
            'download_files' => "Download dos anexos da resposta",
        ],
        'errors' => [
            'no_file_to_download' => "Esta resposta não contém nenhum arquivo anexo.",
        ]
    ],

    'replies' => [
        "list" => [
            "columns" => [
                "label_label" => "Campo",
                "value_label" => "Valor",
            ],
        ],
    ]

];

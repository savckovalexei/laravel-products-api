<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Products API',
                'version' => '1.0.0',
                'description' => 'API для управления товарами с фильтрацией, поиском и пагинацией',
            ],
            'routes' => [
                'api' => 'api/documentation',  // Изменено с api-docs
            ],
            'paths' => [
                'use_absolute_path' => false,
                'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),
                'annotations' => base_path('app'),
                'docs' => storage_path('api-docs'),
            ],
        ],
    ],
    'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', true),
    'proxy' => false,
];
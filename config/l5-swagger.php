<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Minha API',
                'description' => 'Documentação da API',
                'version' => '1.0.0', // Adicionei a versão, é uma boa prática
            ],

            'routes' => [
                /*
                 * Rota para acessar a interface de documentação da API
                 */
                'api' => 'api/documentation',
            ],

            'paths' => [
                /*
                 * Defina como true para usar o caminho absoluto nos assets
                 */
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),

                /*
                 * Caminho onde os assets do Swagger UI serão armazenados
                 */
                'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),
                /*
                 * Arquivo de documentação com a menção dos esquemas necessarios na nossa aplicação
                 */
                'docs' => base_path('app/Documentation'),
                /*
                 * Nome do arquivo JSON que será gerado com a documentação
                 */
                'docs_json' => 'api-docs.json',

                /*
                 * Nome do arquivo YAML (se você preferir gerar esse formato)
                 */
                'docs_yaml' => 'api-docs.yaml',

                /*
                 * Formato da documentação que será usado no Swagger UI (json ou yaml)
                 */
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),

                /*
                 * Caminho absoluto para o diretório onde as anotações do Swagger estão armazenadas
                 */
                'annotations' => [
                    base_path('app'),
                    base_path('app/Http/Resources'), // Adicione esta linha
                ],
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            /*
             * Rota para acessar as anotações do Swagger
             */
            'docs' => 'docs',

            /*
             * Rota para o callback de autenticação OAuth2
             */
            'oauth2_callback' => 'api/oauth2-callback',

            /*
             * Middleware para controlar o acesso à documentação da API
             */
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],

            /*
             * Opções para os grupos de rotas
             */
            'group_options' => [],
        ],

        'paths' => [
            /*
             * Caminho absoluto para onde as anotações parseadas serão armazenadas
             */
            'docs' => storage_path('api-docs'),

            /*
             * Caminho absoluto para onde as views da documentação serão exportadas
             */
            'views' => base_path('resources/views/vendor/l5-swagger'),

            /*
             * Caminho base da API (geralmente se você usa algo como /v1, /api)
             */
            'base' => env('L5_SWAGGER_BASE_PATH', null),

            /*
             * Diretórios que devem ser excluídos da varredura de anotações
             */
            'excludes' => [],
        ],

        'scanOptions' => [
            /**
             * Configuração para os processadores padrão. Permite passar a configuração para o swagger-php.
             *
             * @link https://zircote.github.io/swagger-php/reference/processors.html
             */
            'default_processors_configuration' => [
                // Exemplo:
                // 'operationId.hash' => true,
                // 'pathFilter' => [
                //    'tags' => [
                //        '/pets/',
                //        '/store/',
                //    ],
                // ],
            ],

            /**
             * Analisador de rotas: usa o padrão \OpenApi\StaticAnalyser .
             */
            'analyser' => null,

            /**
             * Análise: usa uma nova instância de \OpenApi\Analysis .
             */
            'analysis' => null,

            /**
             * Processadores de query customizados para os caminhos.
             * @link https://github.com/zircote/swagger-php/tree/master/Examples/processors/schema-query-parameter
             */
            'processors' => [
                // new \App\SwaggerProcessors\SchemaQueryParameter(),
            ],

            /*
             * Padrão de arquivo para varredura (default: *.php)
             */
            'pattern' => null,

            /*
             * Caminho absoluto para diretórios que devem ser excluídos da varredura
             */
            'exclude' => [],

            /*
             * Permite gerar especificações para OpenAPI 3.0.0 ou 3.1.0.
             * Por padrão, será gerado com a versão 3.0.0
             */
            'open_api_spec_version' => env('L5_SWAGGER_OPEN_API_SPEC_VERSION', \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION),
        ],

        /*
         * Definições de segurança da API. Serão geradas no arquivo de documentação.
         */
        'securityDefinitions' => [
            'securitySchemes' => [
                'sanctum' => [
                    'type' => 'apiKey',
                    'description' => 'Entre com o token no formato (Bearer <token>)',
                    'name' => 'Authorization',
                    'in' => 'header',
                ],
            ],
            'security' => [
                [
                    'sanctum' => [],
                ],
            ],
        ],

        /*
         * Gera a documentação sempre que a aplicação for acessada.
         * Defina como false em produção para evitar sobrecarga.
         */
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),

        /*
         * Gera uma cópia da documentação em formato YAML (se ativado)
         */
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),

        /*
         * Configuração de proxy - necessário para balanceadores de carga como o AWS
         */
        'proxy' => false,

        /*
         * Configuração do plugin para pegar configurações externas do Swagger UI
         */
        'additional_config_url' => null,

        /*
         * Classificação das operações na UI. Pode ser 'alpha' (por caminho) ou 'method' (por método HTTP).
         */
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),

        /*
         * Passa o parâmetro validatorUrl para a inicialização do Swagger UI no lado do JS.
         */
        'validator_url' => null,

        /*
         * Configurações da UI do Swagger
         */
        'ui' => [
            'display' => [
                'dark_mode' => env('L5_SWAGGER_UI_DARK_MODE', false),
                'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),
                'filter' => env('L5_SWAGGER_UI_FILTERS', true),
            ],

            'authorization' => [
                'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),
                'oauth2' => [
                    'use_pkce_with_authorization_code_grant' => false,
                ],
            ],
        ],

        /*
         * Definições de constantes que podem ser usadas nas anotações
         */
        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://meu-host.com'),
        ],
    ],
];

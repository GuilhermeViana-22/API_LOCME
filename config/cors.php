<?php

return [
    'paths' => ['api/*'], // Ou ['*'] se quiser para todas as rotas
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://bobflow-client.vercel.app',
        'http://localhost:5173' // para testes locais, se necessário
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // agora pode ser true porque tirou o '*'
];

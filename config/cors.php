<?php

return [
    'paths' => ['*'], // Permite todos os caminhos
    'allowed_methods' => ['*'], // Permite todos os métodos
    'allowed_origins' => ['*'], // Permite todas as origens
    'allowed_origins_patterns' => [], // Padrões de origens permitidas (vazio)
    'allowed_headers' => ['*'], // Permite todos os cabeçalhos
    'exposed_headers' => [], // Cabeçalhos expostos
    'max_age' => 0, // Tempo de cache
    'supports_credentials' => false, // Deve ser false quando allowed_origins é *
];

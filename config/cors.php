<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Bisa diganti domain tertentu
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['Authorization', 'Set-Cookie'],
    'supports_credentials' => true, // HARUS TRUE agar cookie bisa dikirim!
];


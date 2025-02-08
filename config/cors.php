<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:3000',
        'http://103.127.96.144:8101',
        'http://103.127.96.144:8121',
        'http://localhost:8121',
    ], // Ganti dengan URL frontend Anda
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Pastikan ini true jika menggunakan cookie atau sesi
];


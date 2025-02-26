<?php

use Illuminate\Support\Str;

return [

    'driver' => env('SESSION_DRIVER', 'file'),

    'lifetime' => env('SESSION_LIFETIME', 120),

    'expire_on_close' => false,

    'encrypt' => false,

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

    'table' => 'sessions',

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
    ),

    'path' => '/',

    'domain' => env('SESSION_DOMAIN', null), // Pastikan domain diset jika multi-domain digunakan

    'secure' => env('SESSION_SECURE_COOKIE', true), // Pastikan cookie hanya dikirim melalui HTTPS

    'http_only' => true, // Cookie tidak bisa diakses via JavaScript

    'same_site' => 'none', // Harus 'none' agar dapat digunakan dengan credentials: 'include'

    'partitioned' => false, // Tidak perlu mengaktifkan partitioned cookie

];

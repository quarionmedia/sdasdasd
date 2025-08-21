<?php

// Projenin genel ayar dosyası
return [
    // Veritabanı bağlantı ayarları
    'db' => [
        'host' => 'localhost',
        'dbname' => 'muvixtvdb',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ],

    // Dış servislerin ayarları
    'services' => [
        'tmdb' => [
            // Sizin TMDb API Anahtarınız
            'api_key' => '88c4b87bb42b453174d8e4cf9b5b7863'
        ]
    ]
];
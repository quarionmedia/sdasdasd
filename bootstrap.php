<?php

// Hata raporlamayı aç (geliştirme için)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Session'ı her sayfada otomatik olarak başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Merkezi Ayar Yöneticisini ve diğer temel dosyaları yükle
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/app/helpers.php';

// Uygulama ayarlarını yükle (bu, tüm ayarları bir kere yükler ve hafızada tutar)
Config::load('database');

?>
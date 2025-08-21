<?php

// Hata raporlamayı aç (geliştirme için)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- Çekirdek Dosyaları Yükle ---
require_once '../bootstrap.php';

// --- COMPOSER AUTOLOADER ---
// Bu tek satır, vendor klasöründeki kütüphaneleri (PHPMailer) VE 
// app/Controllers ile app/Models klasörlerindeki TÜM sınıflarımızı
// ihtiyaç duyulduğunda otomatik olarak yükler.
require_once '../vendor/autoload.php';


// URL'yi al ve Router'ı çalıştır
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
Router::route($url);
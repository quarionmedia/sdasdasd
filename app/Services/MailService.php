<?php

namespace App\Services;

// PHPMailer sınıflarını global isim alanından import et
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailService {
    private $mailer;

    public function __construct() {
        // Yeni bir PHPMailer nesnesi oluştur; `true` parametresi Exception'ları aktif eder
        $this->mailer = new PHPMailer(true);

        try {
            // Sunucu Ayarları (veritabanımızdan geliyor)
            //$this->mailer->SMTPDebug = SMTP::DEBUG_SERVER; // Hata ayıklama için bu satırı aktif edebilirsiniz
            $this->mailer->isSMTP();
            $this->mailer->Host       = setting('smtp_host');
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = setting('smtp_user');
            $this->mailer->Password   = setting('smtp_pass');
            $this->mailer->SMTPSecure = setting('smtp_secure');
            $this->mailer->Port       = setting('smtp_port');
            $this->mailer->CharSet    = 'UTF-8';

            // Gönderen Bilgileri
            $this->mailer->setFrom(setting('site_email'), setting('site_name'));
            
        } catch (Exception $e) {
            // Bu hatayı daha sonra loglayabiliriz
            die("Mailer kurulamadı. Hata: {$this->mailer->ErrorInfo}");
        }
    }

    /**
     * Bir e-posta gönderir.
     * @param string $toEmail Alıcının e-posta adresi.
     * @param string $toName Alıcının adı.
     * @param string $subject E-posta konusu.
     * @param string $body E-postanın HTML içeriği.
     * @return bool Başarılı olursa true, olmazsa false döner.
     */
    public function send($toEmail, $toName, $subject, $body) {
        try {
            // Alıcılar
            $this->mailer->addAddress($toEmail, $toName);

            // İçerik
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->AltBody = strip_tags($body); // HTML desteklemeyen e-posta istemcileri için

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // Hata ayıklama için: echo "Mesaj gönderilemedi. Hata: {$this->mailer->ErrorInfo}";
            return false;
        }
    }
}
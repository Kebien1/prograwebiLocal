<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Rutas ajustadas a tu estructura: config/ está al mismo nivel que phpmailer/
require_once dirname(__DIR__) . '/phpmailer/PHPMailer.php';
require_once dirname(__DIR__) . '/phpmailer/SMTP.php';
require_once dirname(__DIR__) . '/phpmailer/Exception.php';

function crearMailer() {
    $mail = new PHPMailer(true);
    try {
        // Configuración del Servidor (Gmail en este ejemplo)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        // Coloca aquí tus credenciales reales o usa variables de entorno
        $mail->Username   = 'colombiamasterg@gmail.com'; 
        $mail->Password   = 'zzpz wehx kbnw mqek'; // Tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Remitente por defecto
        $mail->setFrom('colombiamasterg@gmail.com', 'EduPlatform Soporte');
        
        return $mail;
    } catch (Exception $e) {
        return null;
    }
}
?>
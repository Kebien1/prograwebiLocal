<?php
// Archivo: includes/mail_config.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// CORRECCIÓN: El orden es importante y faltaban las barras '/' al inicio
require_once __DIR__ . '/../phpmailer/Exception.php'; // Exception va PRIMERO
require_once __DIR__ . '/../phpmailer/PHPMailer.php'; // PHPMailer va SEGUNDO
require_once __DIR__ . '/../phpmailer/SMTP.php';      // SMTP va TERCERO

function crearMailer(): PHPMailer {
    $mail = new PHPMailer(true);
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'colombiamasterg@gmail.com'; 
    $mail->Password   = 'zzpz wehx kbnw mqek';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    
    // Configuración del remitente por defecto
    $mail->setFrom('colombiamasterg@gmail.com', 'Sistema PrograWeb');
    
    return $mail;
}
?>
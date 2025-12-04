<?php
// test_envio_simple.php - Prueba rápida de envío de email
header('Content-Type: application/json');

// Cargar configuración
require_once 'email_config.php';

// Verificar PHPMailer
if (!file_exists('vendor/autoload.php')) {
    echo json_encode([
        'status' => 'error',
        'message' => 'PHPMailer no está instalado',
        'solucion' => 'Ejecutar: composer require phpmailer/phpmailer'
    ]);
    exit;
}

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Obtener email de destino
$destinatario = $_GET['email'] ?? 'test@example.com';

$mail = new PHPMailer(true);

try {
    // Configurar SMTP
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port = SMTP_PORT;
    $mail->CharSet = 'UTF-8';

    // Configurar remitente y destinatario
    $mail->setFrom(FROM_EMAIL, FROM_NAME);
    $mail->addAddress($destinatario);

    // Contenido del email
    $mail->isHTML(true);
    $mail->Subject = 'Prueba HomePass IoT';
    $mail->Body = '<h2>✅ El email funciona correctamente</h2><p>Tu sistema puede enviar emails a cualquier dirección.</p>';
    $mail->AltBody = 'El email funciona correctamente';

    // Enviar
    $mail->send();

    echo json_encode([
        'status' => 'success',
        'message' => 'Email enviado exitosamente',
        'destinatario' => $destinatario,
        'puede_enviar_a_cualquier_email' => true,
        'configuracion' => [
            'smtp_host' => SMTP_HOST,
            'smtp_port' => SMTP_PORT,
            'remitente' => FROM_EMAIL
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al enviar email',
        'error' => $mail->ErrorInfo,
        'destinatario' => $destinatario,
        'puede_enviar_a_cualquier_email' => 'NO - Hay un problema de configuración'
    ]);
}
?>


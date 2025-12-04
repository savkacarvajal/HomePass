<?php
// test_email.php - Probar envío de email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: text/html; charset=utf-8');

echo "<h1>🧪 Prueba de Envío de Email</h1>";

// Verificar configuración
if (!file_exists('email_config.php')) {
    die("❌ Error: email_config.php no encontrado");
}

if (!file_exists('vendor/autoload.php')) {
    die("❌ Error: PHPMailer no instalado. Ejecuta: composer require phpmailer/phpmailer");
}

require_once 'email_config.php';
require 'vendor/autoload.php';

// Email de destino
$destinatario = isset($_GET['to']) ? $_GET['to'] : 'savkacarvajalg1@gmail.com';

echo "<p><strong>Destinatario:</strong> $destinatario</p>";
echo "<p><strong>Configuración SMTP:</strong></p>";
echo "<ul>";
echo "<li>Host: " . SMTP_HOST . "</li>";
echo "<li>Port: " . SMTP_PORT . "</li>";
echo "<li>Username: " . SMTP_USERNAME . "</li>";
echo "<li>From: " . FROM_EMAIL . "</li>";
echo "</ul>";

echo "<hr>";
echo "<h3>Enviando email de prueba...</h3>";

$mail = new PHPMailer(true);

try {
    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port = SMTP_PORT;
    $mail->CharSet = 'UTF-8';

    // Debug (comentar en producción)
    $mail->SMTPDebug = 2; // Muestra información detallada
    $mail->Debugoutput = 'html';

    // Remitente y destinatario
    $mail->setFrom(FROM_EMAIL, FROM_NAME);
    $mail->addAddress($destinatario);

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = '✅ Prueba de Email - HomePass IoT';
    $mail->Body = '
    <h2>¡Email de Prueba Exitoso!</h2>
    <p>Este es un email de prueba desde HomePass IoT.</p>
    <p><strong>Fecha:</strong> ' . date('Y-m-d H:i:s') . '</p>
    <p><strong>Servidor:</strong> ' . ($_SERVER['SERVER_NAME'] ?? 'Desconocido') . '</p>
    <hr>
    <p style="color: #666; font-size: 12px;">Este es un mensaje automático de prueba.</p>
    ';
    $mail->AltBody = 'Email de prueba desde HomePass IoT. Fecha: ' . date('Y-m-d H:i:s');

    // Enviar
    if ($mail->send()) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h3 style='color: #155724; margin: 0;'>✅ EMAIL ENVIADO EXITOSAMENTE</h3>";
        echo "<p style='color: #155724;'>Revisa la bandeja de entrada de: <strong>$destinatario</strong></p>";
        echo "<p style='color: #155724; font-size: 12px;'>Si no lo ves, revisa la carpeta de SPAM</p>";
        echo "</div>";
    }

} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3 style='color: #721c24; margin: 0;'>❌ ERROR AL ENVIAR EMAIL</h3>";
    echo "<p style='color: #721c24;'><strong>Error:</strong> " . $mail->ErrorInfo . "</p>";
    echo "<p style='color: #721c24; font-size: 12px;'><strong>Excepción:</strong> " . $e->getMessage() . "</p>";
    echo "</div>";

    echo "<h4>Posibles soluciones:</h4>";
    echo "<ul>";
    echo "<li>Verifica que la contraseña de aplicación Gmail sea correcta (16 caracteres)</li>";
    echo "<li>Asegúrate de tener activada la verificación en 2 pasos en Gmail</li>";
    echo "<li>Intenta generar una nueva contraseña de aplicación</li>";
    echo "<li>Verifica que PHPMailer esté instalado: <code>composer require phpmailer/phpmailer</code></li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h4>Comandos útiles:</h4>";
echo "<pre>";
echo "# Verificar instalación de PHPMailer\n";
echo "ls -la vendor/phpmailer/\n\n";
echo "# Reinstalar PHPMailer\n";
echo "cd /var/www/html\n";
echo "composer require phpmailer/phpmailer\n\n";
echo "# Ver logs de Apache\n";
echo "sudo tail -50 /var/log/httpd/error_log\n";
echo "</pre>";

echo "<hr>";
echo "<p><a href='test_email.php?to=$destinatario' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reintentar</a></p>";
?>


<?php
// test_email.php - Prueba de envío de emails

header('Content-Type: application/json; charset=utf-8');
error_reporting(0);
ini_set('display_errors', '0');

$response = array('status' => 'error', 'message' => 'Iniciando prueba...');

try {
    // Verificar si PHPMailer está instalado
    if (file_exists('vendor/autoload.php')) {
        require 'vendor/autoload.php';

        if (file_exists('email_config.php')) {
            require 'email_config.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            // Configuración SMTP
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port = SMTP_PORT;
            $mail->CharSet = 'UTF-8';

            // Remitente y destinatario
            $mail->setFrom(FROM_EMAIL, FROM_NAME);

            // Obtener email de destino
            $to_email = isset($_POST['email']) ? $_POST['email'] : SMTP_USERNAME;
            $mail->addAddress($to_email);

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Prueba de Email - PNKCL IoT';
            $mail->Body = '
            <html>
            <body style="font-family: Arial, sans-serif;">
                <div style="background-color: #4CAF50; color: white; padding: 20px; text-align: center;">
                    <h1>✅ ¡Funciona!</h1>
                </div>
                <div style="padding: 20px;">
                    <h2>Sistema de Emails Configurado Correctamente</h2>
                    <p>Este es un email de prueba del sistema PNKCL IoT.</p>
                    <p>Si estás viendo este mensaje, significa que:</p>
                    <ul>
                        <li>✅ PHPMailer está instalado</li>
                        <li>✅ La configuración SMTP es correcta</li>
                        <li>✅ El servidor puede enviar emails</li>
                    </ul>
                    <p><strong>Próximo paso:</strong> Ya puedes usar la recuperación de contraseña con envío de emails.</p>
                </div>
                <div style="background-color: #f4f4f4; padding: 10px; text-align: center; font-size: 12px; color: #666;">
                    <p>&copy; 2025 PNKCL IoT - Sistema de Control Inteligente</p>
                </div>
            </body>
            </html>
            ';

            $mail->AltBody = '¡Funciona! El sistema de emails está configurado correctamente.';

            $mail->send();

            $response['status'] = 'success';
            $response['message'] = 'Email de prueba enviado correctamente a ' . $to_email;
            $response['method'] = 'PHPMailer';

        } else {
            $response['message'] = 'Error: email_config.php no encontrado. Por favor configúralo primero.';
        }

    } else {
        // Intentar con mail() si PHPMailer no está disponible
        $to_email = isset($_POST['email']) ? $_POST['email'] : 'test@example.com';
        $subject = 'Prueba de Email - PNKCL IoT';
        $message = '¡Funciona! El sistema de emails está configurado correctamente (usando mail()).';
        $headers = 'From: PNKCL IoT <noreply@pnkcl.com>' . "\r\n";
        $headers .= 'Content-Type: text/plain; charset=UTF-8' . "\r\n";

        if (mail($to_email, $subject, $message, $headers)) {
            $response['status'] = 'success';
            $response['message'] = 'Email de prueba enviado con mail() a ' . $to_email;
            $response['method'] = 'mail()';
            $response['warning'] = 'PHPMailer no está instalado. Considera instalarlo para mejor confiabilidad.';
        } else {
            $response['message'] = 'Error: No se pudo enviar el email. PHPMailer no está instalado y mail() falló.';
            $response['suggestion'] = 'Instala PHPMailer con: composer require phpmailer/phpmailer';
        }
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error al enviar email: ' . $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);


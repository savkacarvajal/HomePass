<?php
// solicitar_codigo_con_email.php - VERSIÓN CON ENVÍO REAL DE EMAIL
// ⚠️ Requiere PHPMailer instalado: composer require phpmailer/phpmailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Limpiar buffer
while (ob_get_level()) {
    ob_end_clean();
}
ob_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(0);
ini_set('display_errors', '0');

$response = array('status' => 'error', 'message' => 'Email no proporcionado');

try {
    // Cargar configuración
    if (!file_exists('conexion.php') || !file_exists('email_config.php')) {
        $response['message'] = 'Error: Archivos de configuración no encontrados';
        ob_end_clean();
        echo json_encode($response);
        exit;
    }

    require_once 'conexion.php';
    require_once 'email_config.php';

    // Verificar si PHPMailer está instalado
    if (!file_exists('vendor/autoload.php')) {
        $response['message'] = 'Error: PHPMailer no está instalado. Ejecuta: composer require phpmailer/phpmailer';
        ob_end_clean();
        echo json_encode($response);
        exit;
    }

    require 'vendor/autoload.php';

    if (!isset($conn)) {
        $response['message'] = 'Error: No se pudo conectar a la base de datos';
        ob_end_clean();
        echo json_encode($response);
        exit;
    }

    $GENERIC_SUCCESS_MESSAGE = 'Si el email está registrado, recibirás un código de restablecimiento.';

    if (isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        // Verificar si el email existe
        $stmt_user = $conn->prepare("SELECT nombre, apellido FROM usuarios WHERE email = ?");
        if (!$stmt_user) {
            $response['message'] = 'Error SQL: ' . $conn->error;
            ob_end_clean();
            echo json_encode($response);
            exit;
        }

        $stmt_user->bind_param("s", $email);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        $email_exists = $result_user->num_rows > 0;
        $usuario_data = $email_exists ? $result_user->fetch_assoc() : null;
        $stmt_user->close();

        if ($email_exists) {
            // Generar código
            try {
                $code = str_pad(random_int(10000, 99999), 5, '0', STR_PAD_LEFT);
            } catch (Exception $e) {
                $response['message'] = 'Error interno del servidor.';
                ob_end_clean();
                echo json_encode($response);
                exit;
            }

            // Borrar códigos antiguos
            $stmt_delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
            if ($stmt_delete) {
                $stmt_delete->bind_param("s", $email);
                $stmt_delete->execute();
                $stmt_delete->close();
            }

            // Insertar nuevo código
            $stmt_insert = $conn->prepare("INSERT INTO password_resets (email, code) VALUES (?, ?)");
            if (!$stmt_insert) {
                $response['message'] = 'Error SQL: ' . $conn->error;
                ob_end_clean();
                echo json_encode($response);
                exit;
            }

            $stmt_insert->bind_param("ss", $email, $code);

            if (!$stmt_insert->execute()) {
                $response['message'] = 'Error al guardar código: ' . $stmt_insert->error;
                ob_end_clean();
                echo json_encode($response);
                exit;
            }
            $stmt_insert->close();

            // ENVIAR EMAIL REAL
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

                // Remitente y destinatario
                $mail->setFrom(FROM_EMAIL, FROM_NAME);
                $mail->addAddress($email, $usuario_data['nombre'] . ' ' . $usuario_data['apellido']);

                // Contenido del email
                $mail->isHTML(true);
                $mail->Subject = 'Código de Recuperación de Contraseña - HomePass IoT';

                $mail->Body = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
                        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; }
                        .header h1 { color: white; margin: 0; font-size: 24px; }
                        .content { padding: 40px 30px; }
                        .code-box { background: #f8f9fa; border: 2px dashed #667eea; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0; }
                        .code { font-size: 36px; font-weight: bold; color: #667eea; letter-spacing: 5px; }
                        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px; }
                        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>🔐 HomePass IoT</h1>
                            <p style="color: white; margin: 10px 0 0 0;">Sistema de Control de Acceso</p>
                        </div>
                        <div class="content">
                            <h2 style="color: #333;">Hola ' . htmlspecialchars($usuario_data['nombre']) . ',</h2>
                            <p style="color: #666; line-height: 1.6;">Has solicitado restablecer tu contraseña. Usa el siguiente código de verificación:</p>

                            <div class="code-box">
                                <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">Tu código de verificación es:</p>
                                <div class="code">' . $code . '</div>
                            </div>

                            <div class="warning">
                                <strong>⏰ Importante:</strong> Este código expira en <strong>15 minutos</strong>.
                            </div>

                            <p style="color: #666; line-height: 1.6;">Si no solicitaste este cambio, puedes ignorar este mensaje. Tu contraseña no será modificada.</p>

                            <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">

                            <p style="color: #999; font-size: 14px; line-height: 1.6;">
                                <strong>Consejos de seguridad:</strong><br>
                                • No compartas este código con nadie<br>
                                • Usa una contraseña segura (mínimo 8 caracteres)<br>
                                • Incluye mayúsculas, minúsculas, números y símbolos
                            </p>
                        </div>
                        <div class="footer">
                            <p>Este es un mensaje automático de HomePass IoT<br>
                            Proyecto de Aplicaciones Móviles para IoT - INACAP 2025</p>
                            <p style="margin-top: 10px;">Desarrollado por: Savka Carvajal & Dante Gutierrez</p>
                        </div>
                    </div>
                </body>
                </html>';

                $mail->AltBody = 'Tu código de recuperación es: ' . $code . '. Este código expira en 15 minutos.';

                // Enviar email
                if ($mail->send()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Código enviado a tu correo electrónico. Revisa tu bandeja de entrada y spam.';
                    // NO mostrar código en producción
                    // $response['debug_code'] = $code; // Solo para desarrollo
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error al enviar email: ' . $mail->ErrorInfo;
                }

            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = 'Error al enviar email: ' . $e->getMessage();
            }

        } else {
            // Por seguridad, responder igual aunque el email no exista
            $response['status'] = 'success';
            $response['message'] = $GENERIC_SUCCESS_MESSAGE;
        }
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

ob_end_clean();
echo json_encode($response);

if (isset($conn)) {
    $conn->close();
}
exit;
?>


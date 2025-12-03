<?php
// solicitar_codigo.php - CON PHPMAILER (SMTP)

// Limpiar TODA la salida previa y buffer
while (ob_get_level()) {
    ob_end_clean();
}
ob_start();

// Headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Deshabilitar errores visibles
error_reporting(0);
ini_set('display_errors', '0');

$response = array('status' => 'error', 'message' => 'Email no proporcionado');

try {
    // Incluir conexión
    if (file_exists('conexion.php')) {
        include 'conexion.php';
    } else {
        $response['message'] = 'Error: Archivo conexion.php no encontrado';
        ob_end_clean();
        echo json_encode($response);
        exit;
    }

    // Incluir configuración de email
    if (file_exists('email_config.php')) {
        include 'email_config.php';
    }

    // Verificar conexión
    if (!isset($conn)) {
        $response['message'] = 'Error: No se pudo establecer conexión a la base de datos';
        ob_end_clean();
        echo json_encode($response);
        exit;
    }

    // Leer datos de la solicitud (POST o JSON)
    $request_data = $_POST;
    if (empty($request_data)) {
        $raw_input = file_get_contents("php://input");
        $json_data = json_decode($raw_input, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $request_data = $json_data;
        }
    }

    $GENERIC_SUCCESS_MESSAGE = 'Si el email está registrado, se ha enviado un código de restablecimiento a tu correo.';

    if (isset($request_data['email'])) {
        $email = filter_var($request_data['email'], FILTER_SANITIZE_EMAIL);

        // 1. Verificar si el email existe
        $stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ?");

        if (!$stmt_user) {
            $response['message'] = 'Error SQL al preparar consulta: ' . $conn->error;
            ob_end_clean();
            echo json_encode($response);
            $conn->close();
            exit;
        }

        $stmt_user->bind_param("s", $email);
        $stmt_user->execute();
        $stmt_user->store_result();

        $email_exists = $stmt_user->num_rows > 0;
        $stmt_user->close();

        if ($email_exists) {
            // 2. Generar código seguro de 5 dígitos
            $code = str_pad(random_int(10000, 99999), 5, '0', STR_PAD_LEFT);

            // 3. Eliminar códigos anteriores del mismo email
            $stmt_delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
            if ($stmt_delete) {
                $stmt_delete->bind_param("s", $email);
                $stmt_delete->execute();
                $stmt_delete->close();
            }

            // 4. Insertar el nuevo código
            $stmt_insert = $conn->prepare("INSERT INTO password_resets (email, code, created_at) VALUES (?, ?, NOW())");

            if (!$stmt_insert) {
                $response['message'] = 'Error SQL al insertar código: ' . $conn->error;
                ob_end_clean();
                echo json_encode($response);
                $conn->close();
                exit;
            }

            $stmt_insert->bind_param("ss", $email, $code);

            if ($stmt_insert->execute()) {
                $stmt_insert->close();

                // 5. ENVIAR EMAIL CON PHPMAILER O mail()
                $email_sent = false;
                $email_method = 'none';

                // Intentar con PHPMailer si está disponible
                if (file_exists('vendor/autoload.php') && defined('SMTP_HOST')) {
                    require 'vendor/autoload.php';

                    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                    try {
                        // Configuración del servidor SMTP
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
                        $mail->addAddress($email);

                        // Contenido del email
                        $mail->isHTML(true);
                        $mail->Subject = 'Código de Recuperación de Contraseña - PNKCL IoT';

                        $mail->Body = "
                        <html>
                        <head>
                            <style>
                                body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
                                .container { background-color: #ffffff; padding: 20px; margin: 20px auto; max-width: 600px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                                .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                                .code { font-size: 32px; font-weight: bold; color: #4CAF50; text-align: center; padding: 20px; background-color: #f9f9f9; margin: 20px 0; border-radius: 5px; letter-spacing: 5px; }
                                .content { padding: 20px; color: #333333; line-height: 1.6; }
                                .footer { text-align: center; padding: 20px; color: #999999; font-size: 12px; }
                                .warning { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class='header'>
                                    <h1>🔐 Recuperación de Contraseña</h1>
                                </div>
                                <div class='content'>
                                    <p>Hola,</p>
                                    <p>Has solicitado restablecer tu contraseña en <strong>PNKCL IoT</strong>.</p>
                                    <p>Tu código de verificación es:</p>
                                    <div class='code'>" . $code . "</div>
                                    <div class='warning'>
                                        <strong>⚠️ Importante:</strong>
                                        <ul>
                                            <li>Este código es válido por <strong>15 minutos</strong></li>
                                            <li>No compartas este código con nadie</li>
                                            <li>Si no solicitaste este cambio, ignora este mensaje</li>
                                        </ul>
                                    </div>
                                    <p>Ingresa este código en la aplicación para continuar con el restablecimiento de tu contraseña.</p>
                                </div>
                                <div class='footer'>
                                    <p>Este es un mensaje automático, por favor no respondas a este email.</p>
                                    <p>&copy; 2025 PNKCL IoT - Sistema de Control Inteligente</p>
                                </div>
                            </div>
                        </body>
                        </html>
                        ";

                        $mail->AltBody = "Tu código de recuperación de contraseña es: " . $code . "\n\nEste código es válido por 15 minutos.";

                        $mail->send();
                        $email_sent = true;
                        $email_method = 'PHPMailer';

                    } catch (Exception $e) {
                        // Si PHPMailer falla, intentar con mail()
                        $email_method = 'PHPMailer_failed: ' . $mail->ErrorInfo;
                    }
                }

                // Si PHPMailer no está disponible o falló, usar mail()
                if (!$email_sent) {
                    $subject = "Código de Recuperación de Contraseña - PNKCL IoT";

                    $message = "
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
                            .container { background-color: #ffffff; padding: 20px; margin: 20px auto; max-width: 600px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                            .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                            .code { font-size: 32px; font-weight: bold; color: #4CAF50; text-align: center; padding: 20px; background-color: #f9f9f9; margin: 20px 0; border-radius: 5px; letter-spacing: 5px; }
                            .content { padding: 20px; color: #333333; line-height: 1.6; }
                            .footer { text-align: center; padding: 20px; color: #999999; font-size: 12px; }
                            .warning { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>
                                <h1>🔐 Recuperación de Contraseña</h1>
                            </div>
                            <div class='content'>
                                <p>Hola,</p>
                                <p>Has solicitado restablecer tu contraseña en <strong>PNKCL IoT</strong>.</p>
                                <p>Tu código de verificación es:</p>
                                <div class='code'>" . $code . "</div>
                                <div class='warning'>
                                    <strong>⚠️ Importante:</strong>
                                    <ul>
                                        <li>Este código es válido por <strong>15 minutos</strong></li>
                                        <li>No compartas este código con nadie</li>
                                        <li>Si no solicitaste este cambio, ignora este mensaje</li>
                                    </ul>
                                </div>
                                <p>Ingresa este código en la aplicación para continuar con el restablecimiento de tu contraseña.</p>
                            </div>
                            <div class='footer'>
                                <p>Este es un mensaje automático, por favor no respondas a este email.</p>
                                <p>&copy; 2025 PNKCL IoT - Sistema de Control Inteligente</p>
                            </div>
                        </div>
                    </body>
                    </html>
                    ";

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From: PNKCL IoT <noreply@pnkcl.com>" . "\r\n";

                    $email_sent = mail($email, $subject, $message, $headers);
                    $email_method = $email_sent ? 'mail()' : 'mail()_failed';
                }

                // Responder al cliente
                if ($email_sent) {
                    $response['status'] = 'success';
                    $response['message'] = 'Código enviado correctamente a tu correo electrónico.';
                    if (defined('EMAIL_DEBUG') && EMAIL_DEBUG) {
                        $response['debug'] = array(
                            'code' => $code,
                            'method' => $email_method
                        );
                    }
                } else {
                    // Aunque el email falle, el código está en la BD
                    $response['status'] = 'success';
                    $response['message'] = 'Código generado. (DEBUG: ' . $code . ')';
                    $response['email_warning'] = 'El envío de email falló. Método: ' . $email_method;
                }
            } else {
                $response['message'] = 'Error al guardar el código: ' . $stmt_insert->error;
                $stmt_insert->close();
            }
        } else {
            // Mensaje genérico por seguridad (no revelar si el email existe)
            $response['status'] = 'success';
            $response['message'] = $GENERIC_SUCCESS_MESSAGE;
        }
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error interno: ' . $e->getMessage();
}

// Limpiar el buffer y enviar solo el JSON
ob_end_clean();
echo json_encode($response);

if (isset($conn)) {
    $conn->close();
}
exit;


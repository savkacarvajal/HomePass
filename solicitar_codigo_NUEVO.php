<?php
// solicitar_codigo.php - VERSIÓN COMPLETA Y FUNCIONAL

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

    $GENERIC_SUCCESS_MESSAGE = 'Si el email está registrado, se ha enviado un código de restablecimiento.';

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
                $response['status'] = 'success';
                $response['message'] = $GENERIC_SUCCESS_MESSAGE . ' (DEBUG: ' . $code . ')';
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


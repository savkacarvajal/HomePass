<?php
// solicitar_codigo.php - VERSIÓN CORREGIDA SIN ESPACIOS

// Limpiar cualquier salida previa
if (ob_get_level()) {
    ob_clean();
}

// Headers ANTES de include
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Respuesta por defecto
$response = array('status' => 'error', 'message' => 'Email no proporcionado');

// Intentar incluir conexión
if (file_exists('conexion.php')) {
    include 'conexion.php';
} else {
    $response['message'] = 'Error: Archivo conexion.php no encontrado';
    echo json_encode($response);
    exit;
}

// Verificar que la conexión existe
if (!isset($conn)) {
    $response['message'] = 'Error: No se pudo establecer conexión a la base de datos';
    echo json_encode($response);
    exit;
}

$GENERIC_SUCCESS_MESSAGE = 'Si el email está registrado, se ha enviado un código de restablecimiento.';

if (isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // 1. Verificar si el email existe en 'users'
    $stmt_user = $conn->prepare("SELECT id FROM users WHERE email = ?");

    if (!$stmt_user) {
        $response['message'] = 'Error SQL: ' . $conn->error;
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
        // Generar código seguro
        try {
            $code = str_pad(random_int(10000, 99999), 5, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
            $response['message'] = 'Error interno del servidor.';
            echo json_encode($response);
            $conn->close();
            exit;
        }

        // Borrar códigos antiguos
        $stmt_delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        if ($stmt_delete) {
            $stmt_delete->bind_param("s", $email);
            $stmt_delete->execute();
            $stmt_delete->close();
        }

        // Insertar el nuevo código
        $stmt_insert = $conn->prepare("INSERT INTO password_resets (email, code) VALUES (?, ?)");

        if (!$stmt_insert) {
            $response['message'] = 'Error SQL al preparar: ' . $conn->error;
            echo json_encode($response);
            $conn->close();
            exit;
        }

        $stmt_insert->bind_param("ss", $email, $code);

        if ($stmt_insert->execute()) {
            $response['status'] = 'success';
            $response['message'] = $GENERIC_SUCCESS_MESSAGE . ' (DEBUG: ' . $code . ')';
        } else {
            $response['message'] = 'ERROR SQL al insertar: ' . $conn->error;
        }
        $stmt_insert->close();

    } else {
        // Mensaje genérico por seguridad
        $response['status'] = 'success';
        $response['message'] = $GENERIC_SUCCESS_MESSAGE;
    }
}

echo json_encode($response);
$conn->close();
exit;

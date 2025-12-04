<?php
// apimodificarclave.php - Actualizar contraseña de usuario

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

$response = array('status' => 'error', 'message' => 'Datos incompletos');

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

    if (isset($request_data['email']) && isset($request_data['new_password'])) {
        $email = filter_var($request_data['email'], FILTER_SANITIZE_EMAIL);
        $new_password = $request_data['new_password'];

        // Validar que el email existe
        $stmt_check = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        if (!$stmt_check) {
            $response['message'] = 'Error SQL al verificar usuario: ' . $conn->error;
            ob_end_clean();
            echo json_encode($response);
            $conn->close();
            exit;
        }

        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows === 0) {
            $response['message'] = 'Usuario no encontrado';
            $stmt_check->close();
            ob_end_clean();
            echo json_encode($response);
            $conn->close();
            exit;
        }
        $stmt_check->close();

        // Hash de la nueva contraseña
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Actualizar la contraseña
        $stmt_update = $conn->prepare("UPDATE usuarios SET password_hash = ? WHERE email = ?");
        if (!$stmt_update) {
            $response['message'] = 'Error SQL al actualizar contraseña: ' . $conn->error;
            ob_end_clean();
            echo json_encode($response);
            $conn->close();
            exit;
        }

        $stmt_update->bind_param("ss", $hashed_password, $email);

        if ($stmt_update->execute()) {
            if ($stmt_update->affected_rows > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Contraseña actualizada correctamente';
            } else {
                $response['message'] = 'No se realizaron cambios en la contraseña';
            }
            $stmt_update->close();
        } else {
            $response['message'] = 'Error al ejecutar la actualización: ' . $stmt_update->error;
            $stmt_update->close();
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


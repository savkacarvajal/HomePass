<?php
// validar_codigo.php - VERSIÓN CORREGIDA

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
        $response['message'] = 'Error: No se pudo establecer conexión';
        ob_end_clean();
        echo json_encode($response);
        exit;
    }

    if (isset($_POST['email']) && isset($_POST['code'])) {
        $email = $_POST['email'];
        $code = $_POST['code'];

        // 1. Buscar el código en la base de datos
        $stmt = $conn->prepare("SELECT code, created_at FROM password_resets WHERE email = ?");

        if (!$stmt) {
            $response['message'] = 'Error SQL: ' . $conn->error;
            ob_end_clean();
            echo json_encode($response);
            $conn->close();
            exit;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($stored_code, $created_at);
            $stmt->fetch();

            // 2. Verificar expiración (1 minuto)
            $tiempo_actual = time();
            $tiempo_creacion = strtotime($created_at);
            $tiempo_expiracion = 60;

            if (($tiempo_actual - $tiempo_creacion) > $tiempo_expiracion) {
                // Código expirado
                $stmt->close();
                $stmt_delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
                if ($stmt_delete) {
                    $stmt_delete->bind_param("s", $email);
                    $stmt_delete->execute();
                    $stmt_delete->close();
                }

                $response['message'] = 'El código ha expirado. Por favor solicite uno nuevo.';
            }
            else if ($code !== $stored_code) {
                $response['message'] = 'Código incorrecto';
                $stmt->close();
            }
            else {
                // Código válido
                $stmt->close();
                $stmt_delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
                if ($stmt_delete) {
                    $stmt_delete->bind_param("s", $email);
                    $stmt_delete->execute();
                    $stmt_delete->close();
                }

                $response['status'] = 'success';
                $response['message'] = 'Código validado correctamente';
            }
        } else {
            $response['message'] = 'No se encontró código para este email';
            $stmt->close();
        }
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Limpiar el buffer y enviar solo el JSON
ob_end_clean();
echo json_encode($response);

if (isset($conn)) {
    $conn->close();
}
exit;

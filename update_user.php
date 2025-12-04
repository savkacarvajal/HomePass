<?php
// Modo producción profesional
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer datos desde POST (form data) que envía la app Android
    $id_usuario = isset($_POST['id']) ? intval($_POST['id']) : null;
    $nombres = isset($_POST['nombres']) ? trim($_POST['nombres']) : null;
    $apellidos = isset($_POST['apellidos']) ? trim($_POST['apellidos']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;

    if (!$id_usuario || !$nombres || !$apellidos || !$email) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Todos los campos son requeridos'
        ]);
        exit;
    }

    // Verificar si el email ya existe en otro usuario
    $sql_check = "SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("si", $email, $id_usuario);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'El email ya está registrado por otro usuario'
        ]);
        exit;
    }
    $stmt_check->close();

    // Actualizar usuario (usar nombres de columnas correctos: nombre y apellido singular)
    $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombres, $apellidos, $email, $id_usuario);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Usuario actualizado correctamente'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar usuario: ' . $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido'
    ]);
}

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Usuario actualizado exitosamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al actualizar usuario',
            'error' => $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'mensaje' => 'Método no permitido'
    ]);
}

$conn->close();
?>


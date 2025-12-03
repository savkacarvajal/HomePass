<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_usuario = isset($data['id_usuario']) ? intval($data['id_usuario']) : null;
    $nombres = isset($data['nombres']) ? trim($data['nombres']) : null;
    $apellidos = isset($data['apellidos']) ? trim($data['apellidos']) : null;
    $email = isset($data['email']) ? trim($data['email']) : null;
    $rut = isset($data['rut']) ? trim($data['rut']) : null;
    $telefono = isset($data['telefono']) ? trim($data['telefono']) : null;
    $estado = isset($data['estado']) ? trim($data['estado']) : null;
    $rol = isset($data['rol']) ? trim($data['rol']) : null;

    if (!$id_usuario) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'ID de usuario requerido'
        ]);
        exit;
    }

    // Construir query dinámicamente
    $updates = [];
    $params = [];
    $types = "";

    if ($nombres) {
        $updates[] = "nombres = ?";
        $params[] = $nombres;
        $types .= "s";
    }
    if ($apellidos) {
        $updates[] = "apellidos = ?";
        $params[] = $apellidos;
        $types .= "s";
    }
    if ($email) {
        $updates[] = "email = ?";
        $params[] = $email;
        $types .= "s";
    }
    if ($rut) {
        $updates[] = "rut = ?";
        $params[] = $rut;
        $types .= "s";
    }
    if ($telefono) {
        $updates[] = "telefono = ?";
        $params[] = $telefono;
        $types .= "s";
    }
    if ($estado) {
        $updates[] = "estado = ?";
        $params[] = $estado;
        $types .= "s";
    }
    if ($rol) {
        $updates[] = "rol = ?";
        $params[] = $rol;
        $types .= "s";
    }

    if (empty($updates)) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'No hay datos para actualizar'
        ]);
        exit;
    }

    $sql = "UPDATE usuarios SET " . implode(", ", $updates) . " WHERE id_usuario = ?";
    $params[] = $id_usuario;
    $types .= "i";

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


<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar datos requeridos
    $nombres = isset($data['nombres']) ? trim($data['nombres']) : null;
    $apellidos = isset($data['apellidos']) ? trim($data['apellidos']) : null;
    $email = isset($data['email']) ? trim($data['email']) : null;
    $password = isset($data['password']) ? $data['password'] : null;
    $rut = isset($data['rut']) ? trim($data['rut']) : null;
    $telefono = isset($data['telefono']) ? trim($data['telefono']) : null;
    $id_departamento = isset($data['id_departamento']) ? intval($data['id_departamento']) : null;
    $rol = isset($data['rol']) ? trim($data['rol']) : 'OPERADOR';

    if (!$nombres || !$apellidos || !$email || !$password) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Datos incompletos. Nombres, apellidos, email y contraseña son requeridos.'
        ]);
        exit;
    }

    // Verificar si el email ya existe
    $sql_check = "SELECT id_usuario FROM usuarios WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'El email ya está registrado'
        ]);
        exit;
    }

    // Hash de la contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo usuario
    $sql = "INSERT INTO usuarios (nombres, apellidos, email, password_hash, rut, telefono, id_departamento, rol, estado, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVO', NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssis", $nombres, $apellidos, $email, $password_hash, $rut, $telefono, $id_departamento, $rol);

    if ($stmt->execute()) {
        $id_usuario = $conn->insert_id;

        echo json_encode([
            'success' => true,
            'mensaje' => 'Usuario registrado exitosamente',
            'id_usuario' => $id_usuario
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al registrar usuario',
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


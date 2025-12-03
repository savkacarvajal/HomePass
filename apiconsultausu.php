<?php
?>
$conn->close();

}
    ]);
        'mensaje' => 'Método no permitido'
        'success' => false,
    echo json_encode([
} else {
    $stmt->close();

    }
        ]);
            'mensaje' => 'Usuario no encontrado o inactivo'
            'success' => false,
        echo json_encode([
    } else {
        }
            ]);
                'mensaje' => 'Contraseña incorrecta'
                'success' => false,
            echo json_encode([
        } else {
            ]);
                'usuario' => $usuario
                'mensaje' => 'Login exitoso',
                'success' => true,
            echo json_encode([

            unset($usuario['password_hash']); // No enviar el hash al cliente
            // Login exitoso
        if (password_verify($password, $usuario['password_hash'])) {
        // Verificar contraseña

        $usuario = $result->fetch_assoc();
    if ($result->num_rows > 0) {

    $result = $stmt->get_result();
    $stmt->execute();
    $stmt->bind_param("s", $email);
    $stmt = $conn->prepare($sql);

            WHERE u.email = ? AND u.estado = 'ACTIVO'";
            LEFT JOIN departamentos d ON u.id_departamento = d.id_departamento
            FROM usuarios u
    $sql = "SELECT u.*, d.numero as departamento, d.torre
    // Buscar usuario por email

    }
        exit;
        ]);
            'mensaje' => 'Email y contraseña son requeridos'
            'success' => false,
        echo json_encode([
    if (!$email || !$password) {

    $password = isset($data['password']) ? $data['password'] : null;
    $email = isset($data['email']) ? trim($data['email']) : null;

    $data = json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

require_once 'conexion.php';

header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


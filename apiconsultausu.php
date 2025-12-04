<?php
// =====================================================
// API: CONSULTA DE USUARIO (LOGIN)
// =====================================================

// Modo producción profesional
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer datos desde POST (form data) que envía la app Android
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : null;

    if (!$email || !$contrasena) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email y contraseña son requeridos'
        ]);
        exit;
    }

    // Buscar usuario por email
    $sql = "SELECT u.*, d.numero as departamento, d.torre, d.piso, d.condominio
            FROM usuarios u
            LEFT JOIN departamentos d ON u.id_departamento = d.id_departamento
            WHERE u.email = ? AND u.estado = 'ACTIVO'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verificar contraseña
        if (password_verify($contrasena, $usuario['password_hash'])) {
            // Login exitoso
            unset($usuario['password_hash']); // No enviar el hash al cliente

            echo json_encode([
                'status' => 'success',
                'message' => 'Login exitoso',
                'user' => $usuario
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Contraseña incorrecta'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Usuario no encontrado o inactivo'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido'
    ]);
}

$conn->close();
?>


<?php
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
    $nombres = isset($_POST['nombres']) ? trim($_POST['nombres']) : null;
    $apellidos = isset($_POST['apellidos']) ? trim($_POST['apellidos']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : null;
    $rut = isset($_POST['rut']) ? trim($_POST['rut']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $id_departamento = isset($_POST['id_departamento']) ? intval($_POST['id_departamento']) : 1;
    $rol = isset($_POST['rol']) ? trim($_POST['rol']) : 'OPERADOR';

    // Validar datos requeridos
    if (!$nombres || !$apellidos || !$email || !$contrasena) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Datos incompletos. Nombres, apellidos, email y contraseña son requeridos.'
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
            'status' => 'error',
            'message' => 'El email ya está registrado'
        ]);
        exit;
    }
    $stmt_check->close();

    // Determinar rol: El primer usuario del departamento es ADMINISTRADOR
    $sql_check_admin = "SELECT COUNT(*) as total_admins
                        FROM usuarios
                        WHERE id_departamento = ?
                        AND rol = 'ADMINISTRADOR'";
    $stmt_admin = $conn->prepare($sql_check_admin);
    $stmt_admin->bind_param("i", $id_departamento);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();
    $row_admin = $result_admin->fetch_assoc();
    $tiene_admin = $row_admin['total_admins'] > 0;
    $stmt_admin->close();

    // Si no hay admin en el departamento, este usuario será admin
    if (!$tiene_admin) {
        $rol = 'ADMINISTRADOR';
    } else {
        // Si se envió un rol específico desde la app, usarlo (para casos especiales)
        $rol = isset($_POST['rol']) ? trim($_POST['rol']) : 'OPERADOR';
    }

    // Hash de la contraseña
    $password_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar nuevo usuario (columnas correctas: nombre, apellido, NO fecha_registro)
    $sql = "INSERT INTO usuarios (id_departamento, nombre, apellido, email, password_hash, telefono, rut, rol, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'ACTIVO')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $id_departamento, $nombres, $apellidos, $email, $password_hash, $telefono, $rut, $rol);

    if ($stmt->execute()) {
        $id_usuario = $conn->insert_id;

        echo json_encode([
            'status' => 'success',
            'message' => 'Usuario registrado exitosamente',
            'id_usuario' => $id_usuario,
            'rol' => $rol,
            'es_admin' => ($rol === 'ADMINISTRADOR')
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar usuario. Por favor intente nuevamente.'
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


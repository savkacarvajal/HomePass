<?php
// conexion.example.php - Plantilla de Configuración de Base de Datos
// ⚠️ IMPORTANTE: Copia este archivo como 'conexion.php' y configura tus credenciales

// Configuración de la base de datos HomePass IoT
define('DB_HOST', 'localhost'); // o IP de tu servidor
define('DB_USER', 'root'); // ⚠️ CAMBIAR por tu usuario
define('DB_PASS', 'TU_CONTRASEÑA_AQUI'); // ⚠️ CAMBIAR por tu contraseña
define('DB_NAME', 'homepass_db'); // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'mensaje' => 'Error de conexión a la base de datos',
        'error' => $conn->connect_error
    ]);
    exit;
}

// Establecer el charset a UTF-8
$conn->set_charset("utf8mb4");

// ⚠️ NOTA DE SEGURIDAD:
// - NO subas este archivo a Git
// - Usa contraseñas fuertes
// - En producción, usa variables de entorno
?>


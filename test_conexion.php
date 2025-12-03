<?php
// test_conexion.php - Prueba de conexión a BD

// Limpiar buffers
while (ob_get_level()) {
    ob_end_clean();
}
ob_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

error_reporting(0);
ini_set('display_errors', '0');

if (file_exists('conexion.php')) {
    include 'conexion.php';

    if (isset($conn)) {
        ob_end_clean();
        echo json_encode([
            'status' => 'success',
            'message' => '¡Servidor y BD funcionando!',
            'timestamp' => date('Y-m-d H:i:s'),
            'php_version' => phpversion(),
            'database' => DB_NAME
        ]);
    } else {
        ob_end_clean();
        echo json_encode([
            'status' => 'error',
            'message' => 'Conexión no establecida'
        ]);
    }
} else {
    ob_end_clean();
    echo json_encode([
        'status' => 'error',
        'message' => 'Archivo conexion.php no encontrado'
    ]);
}
exit;

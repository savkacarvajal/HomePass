c<?php
// test_simple.php - Prueba SUPER SIMPLE sin dependencias
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Forzar que se envíe SOLO JSON, sin espacios ni errores
ob_clean();

echo json_encode([
    'status' => 'success',
    'message' => 'Servidor PHP funcionando OK',
    'timestamp' => date('Y-m-d H:i:s')
]);

exit;

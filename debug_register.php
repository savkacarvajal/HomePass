<?php
// debug_register.php - Ver qué datos llegan exactamente
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$debug_info = [
    'metodo' => $_SERVER['REQUEST_METHOD'],
    'POST_data' => $_POST,
    'GET_data' => $_GET,
    'raw_input' => file_get_contents('php://input'),
    'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'no definido',
    'headers' => getallheaders()
];

echo json_encode($debug_info, JSON_PRETTY_PRINT);
?>


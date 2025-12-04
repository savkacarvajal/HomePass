<?php
// test_mysql.php - Probar conexión a base de datos
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$configuraciones = [
    [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => 'HomeP@ss123',
        'db' => 'homepass_db'
    ],
    [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => 'homepass',
        'db' => 'homepass_db'
    ],
    [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db' => 'homepass_db'
    ]
];

$resultado = [
    'servidor' => $_SERVER['SERVER_NAME'] ?? 'Desconocido',
    'php_version' => phpversion(),
    'fecha' => date('Y-m-d H:i:s'),
    'configuraciones_probadas' => []
];

foreach ($configuraciones as $index => $config) {
    $conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);

    $test = [
        'config_num' => $index + 1,
        'host' => $config['host'],
        'user' => $config['user'],
        'db' => $config['db']
    ];

    if ($conn->connect_error) {
        $test['status'] = '❌ Error';
        $test['error'] = $conn->connect_error;
    } else {
        $test['status'] = '✅ Conectado';

        // Verificar tablas
        $tablas_query = $conn->query("SHOW TABLES");
        $tablas = [];
        if ($tablas_query) {
            while($row = $tablas_query->fetch_array()) {
                $tablas[] = $row[0];
            }
        }
        $test['tablas'] = $tablas;
        $test['total_tablas'] = count($tablas);

        // Contar usuarios
        $usuarios_query = $conn->query("SELECT COUNT(*) as total FROM usuarios");
        if ($usuarios_query) {
            $total_usuarios = $usuarios_query->fetch_assoc()['total'];
            $test['total_usuarios'] = $total_usuarios;
        }

        $conn->close();
    }

    $resultado['configuraciones_probadas'][] = $test;
}

echo json_encode($resultado, JSON_PRETTY_PRINT);
?>


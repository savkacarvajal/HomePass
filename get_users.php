<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Parámetros opcionales
    $id_departamento = isset($_GET['id_departamento']) ? intval($_GET['id_departamento']) : null;
    $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : null;

    $sql = "SELECT u.*, d.numero as departamento, d.torre
            FROM usuarios u
            LEFT JOIN departamentos d ON u.id_departamento = d.id_departamento
            WHERE 1=1";

    if ($id_departamento) {
        $sql .= " AND u.id_departamento = $id_departamento";
    }

    if ($busqueda) {
        $sql .= " AND (u.nombres LIKE '%$busqueda%' OR u.apellidos LIKE '%$busqueda%' OR u.email LIKE '%$busqueda%')";
    }

    $sql .= " ORDER BY u.fecha_registro DESC";

    $result = $conn->query($sql);
    $usuarios = [];

    while($row = $result->fetch_assoc()) {
        // No enviar el hash de contraseña
        unset($row['password_hash']);
        $usuarios[] = $row;
    }

    echo json_encode([
        'success' => true,
        'usuarios' => $usuarios,
        'total' => count($usuarios)
    ]);
} else {
    echo json_encode([
        'success' => false,
        'mensaje' => 'Método no permitido'
    ]);
}

$conn->close();
?>


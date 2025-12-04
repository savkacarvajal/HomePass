<?php
// Modo producción profesional
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

try {
    require_once 'conexion.php';

    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Error de conexión: " . ($conn->connect_error ?? 'No disponible'));
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Parámetros opcionales
        $id_departamento = isset($_GET['id_departamento']) ? intval($_GET['id_departamento']) : null;
        $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : null;

        $sql = "SELECT u.*, d.numero as departamento, d.torre
                FROM usuarios u
                LEFT JOIN departamentos d ON u.id_departamento = d.id_departamento
                WHERE 1=1";

        if ($id_departamento) {
            $sql .= " AND u.id_departamento = " . intval($id_departamento);
        }

        if ($busqueda) {
            $busqueda_safe = $conn->real_escape_string($busqueda);
            $sql .= " AND (u.nombre LIKE '%$busqueda_safe%' OR u.apellido LIKE '%$busqueda_safe%' OR u.email LIKE '%$busqueda_safe%')";
        }

        $sql .= " ORDER BY u.fecha_creacion DESC";

        $result = $conn->query($sql);

        if (!$result) {
            throw new Exception("Error en consulta SQL: " . $conn->error);
        }

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

    if (isset($conn)) {
        $conn->close();
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'mensaje' => 'Error del servidor',
        'error' => $e->getMessage(),
        'archivo' => __FILE__
    ]);
}
?>


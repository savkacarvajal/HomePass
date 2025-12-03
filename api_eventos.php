<?php
// =====================================================
// API: HISTORIAL DE EVENTOS/ACCESOS
// Consulta y listado de eventos del sistema
// =====================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once 'conexion.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

if ($metodo !== 'GET') {
    echo json_encode(['success' => false, 'mensaje' => 'Solo se permite GET']);
    exit;
}

try {
    switch ($accion) {
        case 'listar':
            listar_eventos($conn);
            break;

        case 'por_departamento':
            listar_por_departamento($conn);
            break;

        case 'por_usuario':
            listar_por_usuario($conn);
            break;

        case 'recientes':
            listar_recientes($conn);
            break;

        case 'estadisticas':
            obtener_estadisticas($conn);
            break;

        default:
            echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'mensaje' => 'Error del servidor',
        'error' => $e->getMessage()
    ]);
}

$conn->close();

// =====================================================
// FUNCIONES
// =====================================================

function listar_eventos($conn) {
    $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 50;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

    $sql = "SELECT e.*,
                   s.codigo_sensor, s.nombre_sensor, s.tipo AS tipo_sensor,
                   u.nombre AS nombre_usuario, u.apellido AS apellido_usuario,
                   d.numero AS numero_depto, d.torre
            FROM eventos_acceso e
            LEFT JOIN sensores s ON e.id_sensor = s.id_sensor
            LEFT JOIN usuarios u ON e.id_usuario = u.id_usuario
            INNER JOIN departamentos d ON e.id_departamento = d.id_departamento
            ORDER BY e.fecha_hora DESC
            LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $limite, $offset);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $eventos = [];
    while ($row = $resultado->fetch_assoc()) {
        $eventos[] = $row;
    }

    // Contar total
    $count_sql = "SELECT COUNT(*) as total FROM eventos_acceso";
    $count_result = $conn->query($count_sql);
    $total = $count_result->fetch_assoc()['total'];

    echo json_encode([
        'success' => true,
        'datos' => $eventos,
        'total' => $total,
        'limite' => $limite,
        'offset' => $offset
    ]);

    $stmt->close();
}

function listar_por_departamento($conn) {
    $id_departamento = isset($_GET['id_departamento']) ? intval($_GET['id_departamento']) : 0;
    $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 100;

    if ($id_departamento === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de departamento requerido']);
        return;
    }

    $sql = "SELECT e.*,
                   s.codigo_sensor, s.nombre_sensor, s.tipo AS tipo_sensor,
                   u.nombre AS nombre_usuario, u.apellido AS apellido_usuario
            FROM eventos_acceso e
            LEFT JOIN sensores s ON e.id_sensor = s.id_sensor
            LEFT JOIN usuarios u ON e.id_usuario = u.id_usuario
            WHERE e.id_departamento = ?
            ORDER BY e.fecha_hora DESC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id_departamento, $limite);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $eventos = [];
    while ($row = $resultado->fetch_assoc()) {
        $eventos[] = $row;
    }

    echo json_encode([
        'success' => true,
        'datos' => $eventos,
        'total' => count($eventos)
    ]);

    $stmt->close();
}

function listar_por_usuario($conn) {
    $id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : 0;
    $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 100;

    if ($id_usuario === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de usuario requerido']);
        return;
    }

    $sql = "SELECT e.*, s.codigo_sensor, s.nombre_sensor, s.tipo AS tipo_sensor
            FROM eventos_acceso e
            LEFT JOIN sensores s ON e.id_sensor = s.id_sensor
            WHERE e.id_usuario = ?
            ORDER BY e.fecha_hora DESC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id_usuario, $limite);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $eventos = [];
    while ($row = $resultado->fetch_assoc()) {
        $eventos[] = $row;
    }

    echo json_encode([
        'success' => true,
        'datos' => $eventos,
        'total' => count($eventos)
    ]);

    $stmt->close();
}

function listar_recientes($conn) {
    $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;

    $sql = "SELECT e.*,
                   s.codigo_sensor, s.nombre_sensor, s.tipo AS tipo_sensor,
                   u.nombre AS nombre_usuario, u.apellido AS apellido_usuario,
                   d.numero AS numero_depto, d.torre
            FROM eventos_acceso e
            LEFT JOIN sensores s ON e.id_sensor = s.id_sensor
            LEFT JOIN usuarios u ON e.id_usuario = u.id_usuario
            INNER JOIN departamentos d ON e.id_departamento = d.id_departamento
            ORDER BY e.fecha_hora DESC
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $limite);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $eventos = [];
    while ($row = $resultado->fetch_assoc()) {
        $eventos[] = $row;
    }

    echo json_encode([
        'success' => true,
        'datos' => $eventos,
        'total' => count($eventos)
    ]);

    $stmt->close();
}

function obtener_estadisticas($conn) {
    $id_departamento = isset($_GET['id_departamento']) ? intval($_GET['id_departamento']) : 0;
    $fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : date('Y-m-d', strtotime('-7 days'));
    $fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : date('Y-m-d');

    $where_depto = $id_departamento > 0 ? " AND id_departamento = $id_departamento" : "";

    // Total de accesos
    $sql_total = "SELECT COUNT(*) as total FROM eventos_acceso
                  WHERE DATE(fecha_hora) BETWEEN ? AND ? $where_depto";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param('ss', $fecha_desde, $fecha_hasta);
    $stmt_total->execute();
    $total = $stmt_total->get_result()->fetch_assoc()['total'];
    $stmt_total->close();

    // Accesos permitidos
    $sql_permitidos = "SELECT COUNT(*) as permitidos FROM eventos_acceso
                       WHERE resultado = 'PERMITIDO'
                       AND DATE(fecha_hora) BETWEEN ? AND ? $where_depto";
    $stmt_permitidos = $conn->prepare($sql_permitidos);
    $stmt_permitidos->bind_param('ss', $fecha_desde, $fecha_hasta);
    $stmt_permitidos->execute();
    $permitidos = $stmt_permitidos->get_result()->fetch_assoc()['permitidos'];
    $stmt_permitidos->close();

    // Accesos denegados
    $sql_denegados = "SELECT COUNT(*) as denegados FROM eventos_acceso
                      WHERE resultado = 'DENEGADO'
                      AND DATE(fecha_hora) BETWEEN ? AND ? $where_depto";
    $stmt_denegados = $conn->prepare($sql_denegados);
    $stmt_denegados->bind_param('ss', $fecha_desde, $fecha_hasta);
    $stmt_denegados->execute();
    $denegados = $stmt_denegados->get_result()->fetch_assoc()['denegados'];
    $stmt_denegados->close();

    // Eventos por tipo
    $sql_por_tipo = "SELECT tipo_evento, COUNT(*) as cantidad
                     FROM eventos_acceso
                     WHERE DATE(fecha_hora) BETWEEN ? AND ? $where_depto
                     GROUP BY tipo_evento";
    $stmt_por_tipo = $conn->prepare($sql_por_tipo);
    $stmt_por_tipo->bind_param('ss', $fecha_desde, $fecha_hasta);
    $stmt_por_tipo->execute();
    $result_tipos = $stmt_por_tipo->get_result();

    $por_tipo = [];
    while ($row = $result_tipos->fetch_assoc()) {
        $por_tipo[$row['tipo_evento']] = $row['cantidad'];
    }
    $stmt_por_tipo->close();

    echo json_encode([
        'success' => true,
        'periodo' => [
            'desde' => $fecha_desde,
            'hasta' => $fecha_hasta
        ],
        'estadisticas' => [
            'total_eventos' => $total,
            'accesos_permitidos' => $permitidos,
            'accesos_denegados' => $denegados,
            'porcentaje_exito' => $total > 0 ? round(($permitidos / $total) * 100, 2) : 0,
            'por_tipo' => $por_tipo
        ]
    ]);
}
?>


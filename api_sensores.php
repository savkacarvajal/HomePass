<?php
// =====================================================
// API: GESTIÓN DE SENSORES
// CRUD completo para sensores RFID
// =====================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

require_once 'conexion.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

try {
    switch ($metodo) {
        case 'GET':
            if ($accion === 'listar') {
                listar_sensores($conn);
            } elseif ($accion === 'por_departamento') {
                listar_por_departamento($conn);
            } elseif ($accion === 'por_usuario') {
                listar_por_usuario($conn);
            } else {
                echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
            }
            break;

        case 'POST':
            if ($accion === 'agregar') {
                agregar_sensor($conn);
            } else {
                echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
            }
            break;

        case 'PUT':
            if ($accion === 'actualizar_estado') {
                actualizar_estado_sensor($conn);
            } elseif ($accion === 'editar') {
                editar_sensor($conn);
            } else {
                echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
            }
            break;

        case 'DELETE':
            if ($accion === 'eliminar') {
                eliminar_sensor($conn);
            } else {
                echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
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

function listar_sensores($conn) {
    $sql = "SELECT s.*, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario,
                   d.numero AS numero_depto, d.torre
            FROM sensores s
            INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
            INNER JOIN departamentos d ON s.id_departamento = d.id_departamento
            ORDER BY s.fecha_alta DESC";

    $resultado = $conn->query($sql);
    $sensores = [];

    while ($row = $resultado->fetch_assoc()) {
        $sensores[] = $row;
    }

    echo json_encode([
        'success' => true,
        'datos' => $sensores,
        'total' => count($sensores)
    ]);
}

function listar_por_departamento($conn) {
    $id_departamento = isset($_GET['id_departamento']) ? intval($_GET['id_departamento']) : 0;

    if ($id_departamento === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de departamento requerido']);
        return;
    }

    $sql = "SELECT s.*, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario
            FROM sensores s
            INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
            WHERE s.id_departamento = ?
            ORDER BY s.estado, s.fecha_alta DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_departamento);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $sensores = [];
    while ($row = $resultado->fetch_assoc()) {
        $sensores[] = $row;
    }

    echo json_encode([
        'success' => true,
        'datos' => $sensores,
        'total' => count($sensores)
    ]);

    $stmt->close();
}

function listar_por_usuario($conn) {
    $id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : 0;

    if ($id_usuario === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de usuario requerido']);
        return;
    }

    $sql = "SELECT * FROM sensores WHERE id_usuario = ? ORDER BY fecha_alta DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $sensores = [];
    while ($row = $resultado->fetch_assoc()) {
        $sensores[] = $row;
    }

    echo json_encode([
        'success' => true,
        'datos' => $sensores,
        'total' => count($sensores)
    ]);

    $stmt->close();
}

function agregar_sensor($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_departamento = isset($data['id_departamento']) ? intval($data['id_departamento']) : 0;
    $id_usuario = isset($data['id_usuario']) ? intval($data['id_usuario']) : 0;
    $codigo_sensor = isset($data['codigo_sensor']) ? trim($data['codigo_sensor']) : '';
    $nombre_sensor = isset($data['nombre_sensor']) ? trim($data['nombre_sensor']) : '';
    $tipo = isset($data['tipo']) ? strtoupper(trim($data['tipo'])) : 'LLAVERO';
    $estado = isset($data['estado']) ? strtoupper(trim($data['estado'])) : 'ACTIVO';

    // Validaciones
    if ($id_departamento === 0 || $id_usuario === 0 || empty($codigo_sensor)) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Datos incompletos. Se requiere: id_departamento, id_usuario y codigo_sensor'
        ]);
        return;
    }

    if (!in_array($tipo, ['LLAVERO', 'TARJETA'])) {
        echo json_encode(['success' => false, 'mensaje' => 'Tipo debe ser LLAVERO o TARJETA']);
        return;
    }

    if (!in_array($estado, ['ACTIVO', 'INACTIVO', 'PERDIDO', 'BLOQUEADO'])) {
        echo json_encode(['success' => false, 'mensaje' => 'Estado no válido']);
        return;
    }

    // Verificar que el código no exista
    $check_sql = "SELECT id_sensor FROM sensores WHERE codigo_sensor = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('s', $codigo_sensor);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Este código de sensor ya está registrado'
        ]);
        $check_stmt->close();
        return;
    }
    $check_stmt->close();

    // Insertar el sensor
    $sql = "INSERT INTO sensores (id_departamento, id_usuario, codigo_sensor, nombre_sensor, tipo, estado)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iissss', $id_departamento, $id_usuario, $codigo_sensor,
        $nombre_sensor, $tipo, $estado);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Sensor registrado exitosamente',
            'id_sensor' => $stmt->insert_id
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al registrar sensor',
            'error' => $stmt->error
        ]);
    }

    $stmt->close();
}

function actualizar_estado_sensor($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_sensor = isset($data['id_sensor']) ? intval($data['id_sensor']) : 0;
    $nuevo_estado = isset($data['estado']) ? strtoupper(trim($data['estado'])) : '';

    if ($id_sensor === 0 || empty($nuevo_estado)) {
        echo json_encode(['success' => false, 'mensaje' => 'Datos incompletos']);
        return;
    }

    if (!in_array($nuevo_estado, ['ACTIVO', 'INACTIVO', 'PERDIDO', 'BLOQUEADO'])) {
        echo json_encode(['success' => false, 'mensaje' => 'Estado no válido']);
        return;
    }

    $fecha_baja = ($nuevo_estado === 'ACTIVO') ? null : date('Y-m-d H:i:s');

    $sql = "UPDATE sensores SET estado = ?, fecha_baja = ? WHERE id_sensor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $nuevo_estado, $fecha_baja, $id_sensor);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Estado actualizado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al actualizar estado',
            'error' => $stmt->error
        ]);
    }

    $stmt->close();
}

function editar_sensor($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_sensor = isset($data['id_sensor']) ? intval($data['id_sensor']) : 0;
    $nombre_sensor = isset($data['nombre_sensor']) ? trim($data['nombre_sensor']) : '';
    $tipo = isset($data['tipo']) ? strtoupper(trim($data['tipo'])) : '';

    if ($id_sensor === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de sensor requerido']);
        return;
    }

    $sql = "UPDATE sensores SET nombre_sensor = ?, tipo = ? WHERE id_sensor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $nombre_sensor, $tipo, $id_sensor);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Sensor actualizado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al actualizar sensor'
        ]);
    }

    $stmt->close();
}

function eliminar_sensor($conn) {
    $id_sensor = isset($_GET['id_sensor']) ? intval($_GET['id_sensor']) : 0;

    if ($id_sensor === 0) {
        echo json_encode(['success' => false, 'mensaje' => 'ID de sensor requerido']);
        return;
    }

    $sql = "DELETE FROM sensores WHERE id_sensor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_sensor);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Sensor eliminado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al eliminar sensor'
        ]);
    }

    $stmt->close();
}
?>


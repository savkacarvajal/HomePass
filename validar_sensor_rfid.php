<?php
// =====================================================
// API: VALIDAR SENSOR RFID
// Valida si un sensor RFID tiene acceso permitido
// Para uso del NodeMCU
// =====================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'mensaje' => 'Método no permitido. Usar POST'
    ]);
    exit;
}

// Obtener código del sensor
$data = json_decode(file_get_contents('php://input'), true);
$codigo_sensor = isset($data['codigo_sensor']) ? trim($data['codigo_sensor']) : '';

if (empty($codigo_sensor)) {
    echo json_encode([
        'success' => false,
        'resultado' => 'DENEGADO',
        'color' => 'ROJO',
        'abrir_barrera' => false,
        'mensaje' => 'Código de sensor no proporcionado'
    ]);
    exit;
}

try {
    // Buscar el sensor en la base de datos
    $sql = "SELECT s.id_sensor, s.id_usuario, s.id_departamento, s.codigo_sensor,
                   s.nombre_sensor, s.tipo, s.estado,
                   u.nombre, u.apellido, u.estado AS estado_usuario,
                   d.numero AS numero_depto, d.torre
            FROM sensores s
            INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
            INNER JOIN departamentos d ON s.id_departamento = d.id_departamento
            WHERE s.codigo_sensor = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $codigo_sensor);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        // Sensor no encontrado - DENEGAR ACCESO
        registrar_evento_acceso($conn, null, null, null, $codigo_sensor,
            'ACCESO_RECHAZADO', 'DENEGADO', 'Sensor no registrado en el sistema');

        echo json_encode([
            'success' => false,
            'resultado' => 'DENEGADO',
            'color' => 'ROJO',
            'abrir_barrera' => false,
            'mensaje' => 'Sensor no registrado',
            'tiempo_espera' => 0
        ]);
        exit;
    }

    $sensor = $resultado->fetch_assoc();

    // Verificar estado del sensor
    if ($sensor['estado'] !== 'ACTIVO') {
        $tipo_evento = match($sensor['estado']) {
            'INACTIVO' => 'SENSOR_INACTIVO',
            'BLOQUEADO' => 'SENSOR_BLOQUEADO',
            'PERDIDO' => 'SENSOR_PERDIDO',
            default => 'ACCESO_RECHAZADO'
        };

        registrar_evento_acceso($conn, $sensor['id_sensor'], $sensor['id_usuario'],
            $sensor['id_departamento'], $codigo_sensor, $tipo_evento, 'DENEGADO',
            "Sensor en estado: {$sensor['estado']}");

        echo json_encode([
            'success' => false,
            'resultado' => 'DENEGADO',
            'color' => 'ROJO',
            'abrir_barrera' => false,
            'mensaje' => "Sensor {$sensor['estado']}",
            'estado_sensor' => $sensor['estado'],
            'tiempo_espera' => 0
        ]);
        exit;
    }

    // Verificar estado del usuario
    if ($sensor['estado_usuario'] !== 'ACTIVO') {
        registrar_evento_acceso($conn, $sensor['id_sensor'], $sensor['id_usuario'],
            $sensor['id_departamento'], $codigo_sensor, 'ACCESO_RECHAZADO', 'DENEGADO',
            "Usuario en estado: {$sensor['estado_usuario']}");

        echo json_encode([
            'success' => false,
            'resultado' => 'DENEGADO',
            'color' => 'ROJO',
            'abrir_barrera' => false,
            'mensaje' => 'Usuario no activo',
            'tiempo_espera' => 0
        ]);
        exit;
    }

    // ✅ ACCESO PERMITIDO
    registrar_evento_acceso($conn, $sensor['id_sensor'], $sensor['id_usuario'],
        $sensor['id_departamento'], $codigo_sensor, 'ACCESO_VALIDO', 'PERMITIDO',
        'Acceso concedido correctamente');

    echo json_encode([
        'success' => true,
        'resultado' => 'PERMITIDO',
        'color' => 'VERDE',
        'abrir_barrera' => true,
        'tiempo_espera' => 10, // 10 segundos para cerrar automáticamente
        'mensaje' => 'Acceso permitido',
        'usuario' => $sensor['nombre'] . ' ' . $sensor['apellido'],
        'departamento' => $sensor['numero_depto'] . '-' . $sensor['torre'],
        'tipo_sensor' => $sensor['tipo']
    ]);

    $stmt->close();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'resultado' => 'DENEGADO',
        'color' => 'ROJO',
        'abrir_barrera' => false,
        'mensaje' => 'Error del servidor',
        'error' => $e->getMessage()
    ]);
}

$conn->close();

// Función para registrar eventos de acceso
function registrar_evento_acceso($conn, $id_sensor, $id_usuario, $id_departamento,
    $codigo_sensor, $tipo_evento, $resultado, $observaciones) {

    // Si no hay departamento, usar el departamento 1 por defecto
    if ($id_departamento === null) {
        $id_departamento = 1;
    }

    $sql = "INSERT INTO eventos_acceso
            (id_sensor, id_usuario, id_departamento, tipo_evento, resultado, codigo_sensor, observaciones)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiissss', $id_sensor, $id_usuario, $id_departamento,
        $tipo_evento, $resultado, $codigo_sensor, $observaciones);
    $stmt->execute();
    $stmt->close();
}
?>


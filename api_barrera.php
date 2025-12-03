<?php
// =====================================================
// API: CONTROL DE BARRERA
// Control manual de apertura/cierre desde la app
// =====================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

require_once 'conexion.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

try {
    switch ($metodo) {
        case 'GET':
            if ($accion === 'estado') {
                obtener_estado_barrera($conn);
            } else {
                echo json_encode(['success' => false, 'mensaje' => 'Acción no válida']);
            }
            break;

        case 'POST':
            if ($accion === 'abrir') {
                abrir_barrera($conn);
            } elseif ($accion === 'cerrar') {
                cerrar_barrera($conn);
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

function obtener_estado_barrera($conn) {
    $sql = "SELECT eb.*, u.nombre, u.apellido
            FROM estado_barrera eb
            LEFT JOIN usuarios u ON eb.id_usuario_responsable = u.id_usuario
            ORDER BY eb.ultimo_cambio DESC
            LIMIT 1";

    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $estado = $resultado->fetch_assoc();
        echo json_encode([
            'success' => true,
            'estado_actual' => $estado['estado_actual'],
            'ultimo_cambio' => $estado['ultimo_cambio'],
            'accion' => $estado['accion'],
            'responsable' => ($estado['nombre'] ? $estado['nombre'] . ' ' . $estado['apellido'] : 'Sistema')
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'estado_actual' => 'CERRADA',
            'mensaje' => 'Estado inicial'
        ]);
    }
}

function abrir_barrera($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_usuario = isset($data['id_usuario']) ? intval($data['id_usuario']) : null;
    $id_departamento = isset($data['id_departamento']) ? intval($data['id_departamento']) : 1;

    // Verificar permisos del usuario
    if ($id_usuario) {
        $check_sql = "SELECT estado, rol FROM usuarios WHERE id_usuario = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param('i', $id_usuario);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $usuario = $check_result->fetch_assoc();
            if ($usuario['estado'] !== 'ACTIVO') {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Usuario no activo'
                ]);
                $check_stmt->close();
                return;
            }
        }
        $check_stmt->close();
    }

    // Actualizar estado de la barrera
    $conn->begin_transaction();

    try {
        // Actualizar tabla estado_barrera
        $update_sql = "UPDATE estado_barrera
                      SET estado_actual = 'ABIERTA',
                          id_usuario_responsable = ?,
                          accion = 'APERTURA_MANUAL'
                      ORDER BY ultimo_cambio DESC
                      LIMIT 1";

        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('i', $id_usuario);
        $update_stmt->execute();
        $update_stmt->close();

        // Registrar evento de acceso
        $evento_sql = "INSERT INTO eventos_acceso
                      (id_usuario, id_departamento, tipo_evento, resultado, observaciones)
                      VALUES (?, ?, 'APERTURA_MANUAL_APP', 'PERMITIDO', 'Apertura manual desde la aplicación')";

        $evento_stmt = $conn->prepare($evento_sql);
        $evento_stmt->bind_param('ii', $id_usuario, $id_departamento);
        $evento_stmt->execute();
        $evento_stmt->close();

        $conn->commit();

        echo json_encode([
            'success' => true,
            'mensaje' => 'Barrera abierta correctamente',
            'estado_actual' => 'ABIERTA',
            'tiempo_auto_cierre' => 10 // segundos
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al abrir barrera',
            'error' => $e->getMessage()
        ]);
    }
}

function cerrar_barrera($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_usuario = isset($data['id_usuario']) ? intval($data['id_usuario']) : null;
    $id_departamento = isset($data['id_departamento']) ? intval($data['id_departamento']) : 1;

    // Solo ADMINISTRADORES pueden cerrar manualmente
    if ($id_usuario) {
        $check_sql = "SELECT estado, rol FROM usuarios WHERE id_usuario = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param('i', $id_usuario);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $usuario = $check_result->fetch_assoc();
            if ($usuario['estado'] !== 'ACTIVO') {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Usuario no activo'
                ]);
                $check_stmt->close();
                return;
            }

            if ($usuario['rol'] !== 'ADMINISTRADOR') {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Solo administradores pueden cerrar la barrera manualmente'
                ]);
                $check_stmt->close();
                return;
            }
        }
        $check_stmt->close();
    }

    // Actualizar estado de la barrera
    $conn->begin_transaction();

    try {
        // Actualizar tabla estado_barrera
        $update_sql = "UPDATE estado_barrera
                      SET estado_actual = 'CERRADA',
                          id_usuario_responsable = ?,
                          accion = 'CIERRE_MANUAL'
                      ORDER BY ultimo_cambio DESC
                      LIMIT 1";

        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('i', $id_usuario);
        $update_stmt->execute();
        $update_stmt->close();

        // Registrar evento de acceso
        $evento_sql = "INSERT INTO eventos_acceso
                      (id_usuario, id_departamento, tipo_evento, resultado, observaciones)
                      VALUES (?, ?, 'CIERRE_MANUAL_APP', 'PERMITIDO', 'Cierre manual desde la aplicación')";

        $evento_stmt = $conn->prepare($evento_sql);
        $evento_stmt->bind_param('ii', $id_usuario, $id_departamento);
        $evento_stmt->execute();
        $evento_stmt->close();

        $conn->commit();

        echo json_encode([
            'success' => true,
            'mensaje' => 'Barrera cerrada correctamente',
            'estado_actual' => 'CERRADA'
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al cerrar barrera',
            'error' => $e->getMessage()
        ]);
    }
}
?>


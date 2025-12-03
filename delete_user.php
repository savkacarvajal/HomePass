<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id_usuario = isset($data['id_usuario']) ? intval($data['id_usuario']) :
                  (isset($_GET['id']) ? intval($_GET['id']) : null);

    if (!$id_usuario) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'ID de usuario requerido'
        ]);
        exit;
    }

    // Verificar si el usuario tiene sensores asociados
    $sql_check = "SELECT COUNT(*) as total FROM sensores WHERE id_usuario = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_usuario);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row = $result_check->fetch_assoc();

    if ($row['total'] > 0) {
        echo json_encode([
            'success' => false,
            'mensaje' => "No se puede eliminar. El usuario tiene {$row['total']} sensor(es) asociado(s). Elimínelos primero."
        ]);
        exit;
    }

    // Eliminar eventos asociados al usuario
    $sql_eventos = "DELETE FROM eventos_acceso WHERE id_usuario = ?";
    $stmt_eventos = $conn->prepare($sql_eventos);
    $stmt_eventos->bind_param("i", $id_usuario);
    $stmt_eventos->execute();

    // Eliminar usuario
    $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Usuario eliminado exitosamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al eliminar usuario',
            'error' => $stmt->error
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'mensaje' => 'Método no permitido'
    ]);
}

$conn->close();
?>


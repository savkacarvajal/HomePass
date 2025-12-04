<?php
// ver_codigo_temporal.php
// ⚠️ SOLO PARA PRUEBAS - ELIMINAR ANTES DE ENTREGAR
// Este script te permite ver los códigos de recuperación durante desarrollo

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'conexion.php';

$email = isset($_GET['email']) ? trim($_GET['email']) : '';

if (empty($email)) {
    echo json_encode([
        'error' => 'Por favor proporciona un email',
        'ejemplo' => 'ver_codigo_temporal.php?email=tu_email@example.com'
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT code, created_at,
                        TIMESTAMPDIFF(MINUTE, created_at, NOW()) as minutos_transcurridos
                        FROM password_resets
                        WHERE email = ?
                        ORDER BY created_at DESC
                        LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $expiro = $row['minutos_transcurridos'] > 15;

    echo json_encode([
        'email' => $email,
        'codigo' => $row['code'],
        'fecha_creacion' => $row['created_at'],
        'minutos_transcurridos' => $row['minutos_transcurridos'],
        'estado' => $expiro ? '❌ EXPIRADO' : '✅ VÁLIDO',
        'expira_en' => $expiro ? 'YA EXPIRÓ' : (15 - $row['minutos_transcurridos']) . ' minutos'
    ], JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        'email' => $email,
        'mensaje' => 'No hay código de recuperación para este email',
        'sugerencia' => 'Solicita un código desde la app primero'
    ], JSON_PRETTY_PRINT);
}

$stmt->close();
$conn->close();
?>


<?php
// setup_database.php - Script para verificar y crear la estructura de base de datos

header('Content-Type: application/json; charset=utf-8');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'HomeP@ss123');
define('DB_NAME', 'homepass_db');

$response = ['success' => false, 'message' => '', 'details' => []];

try {
    // Conectar sin seleccionar base de datos
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    $response['details'][] = "✓ Conexión establecida con MySQL";

    // Crear base de datos si no existe
    $sql_create_db = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if ($conn->query($sql_create_db)) {
        $response['details'][] = "✓ Base de datos 'homepass_db' verificada/creada";
    } else {
        throw new Exception("Error creando base de datos: " . $conn->error);
    }

    // Seleccionar la base de datos
    $conn->select_db(DB_NAME);

    // Verificar tablas existentes
    $result = $conn->query("SHOW TABLES");
    $existing_tables = [];
    while ($row = $result->fetch_array()) {
        $existing_tables[] = $row[0];
    }

    $response['details'][] = "Tablas existentes: " . (count($existing_tables) > 0 ? implode(', ', $existing_tables) : 'ninguna');

    // Crear tablas si no existen
    $tables_created = 0;

    // 1. Tabla departamentos
    if (!in_array('departamentos', $existing_tables)) {
        $sql = "CREATE TABLE IF NOT EXISTS departamentos (
            id_departamento INT AUTO_INCREMENT PRIMARY KEY,
            numero VARCHAR(10) NOT NULL,
            torre VARCHAR(10),
            piso INT,
            condominio VARCHAR(100),
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_numero (numero),
            INDEX idx_torre (torre)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($conn->query($sql)) {
            $tables_created++;
            $response['details'][] = "✓ Tabla 'departamentos' creada";
        }
    }

    // 2. Tabla usuarios
    if (!in_array('usuarios', $existing_tables)) {
        $sql = "CREATE TABLE IF NOT EXISTS usuarios (
            id_usuario INT AUTO_INCREMENT PRIMARY KEY,
            id_departamento INT NOT NULL,
            nombre VARCHAR(100) NOT NULL,
            apellido VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            telefono VARCHAR(20),
            rut VARCHAR(12),
            rol ENUM('ADMINISTRADOR', 'OPERADOR') DEFAULT 'OPERADOR',
            estado ENUM('ACTIVO', 'INACTIVO', 'BLOQUEADO') DEFAULT 'ACTIVO',
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (id_departamento) REFERENCES departamentos(id_departamento) ON DELETE CASCADE,
            INDEX idx_email (email),
            INDEX idx_departamento (id_departamento),
            INDEX idx_rol (rol),
            INDEX idx_estado (estado)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($conn->query($sql)) {
            $tables_created++;
            $response['details'][] = "✓ Tabla 'usuarios' creada";
        }
    }

    // 3. Tabla sensores
    if (!in_array('sensores', $existing_tables)) {
        $sql = "CREATE TABLE IF NOT EXISTS sensores (
            id_sensor INT AUTO_INCREMENT PRIMARY KEY,
            id_departamento INT NOT NULL,
            id_usuario INT NOT NULL,
            codigo_sensor VARCHAR(50) NOT NULL UNIQUE COMMENT 'UID/MAC de tarjeta o llavero RFID',
            nombre_sensor VARCHAR(100) COMMENT 'Nombre descriptivo del sensor',
            tipo ENUM('LLAVERO', 'TARJETA') NOT NULL,
            estado ENUM('ACTIVO', 'INACTIVO', 'PERDIDO', 'BLOQUEADO') DEFAULT 'ACTIVO',
            fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            fecha_baja TIMESTAMP NULL,
            fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (id_departamento) REFERENCES departamentos(id_departamento) ON DELETE CASCADE,
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
            INDEX idx_codigo (codigo_sensor),
            INDEX idx_departamento (id_departamento),
            INDEX idx_usuario (id_usuario),
            INDEX idx_estado (estado),
            INDEX idx_tipo (tipo)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($conn->query($sql)) {
            $tables_created++;
            $response['details'][] = "✓ Tabla 'sensores' creada";
        }
    }

    // 4. Tabla eventos_acceso
    if (!in_array('eventos_acceso', $existing_tables)) {
        $sql = "CREATE TABLE IF NOT EXISTS eventos_acceso (
            id_evento INT AUTO_INCREMENT PRIMARY KEY,
            id_sensor INT,
            id_usuario INT,
            id_departamento INT NOT NULL,
            tipo_evento ENUM(
                'ACCESO_VALIDO',
                'ACCESO_RECHAZADO',
                'APERTURA_MANUAL_APP',
                'CIERRE_MANUAL_APP',
                'SENSOR_INACTIVO',
                'SENSOR_BLOQUEADO',
                'SENSOR_PERDIDO'
            ) NOT NULL,
            resultado ENUM('PERMITIDO', 'DENEGADO') NOT NULL,
            codigo_sensor VARCHAR(50) COMMENT 'Código del sensor que intentó acceder',
            fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            observaciones TEXT,
            FOREIGN KEY (id_sensor) REFERENCES sensores(id_sensor) ON DELETE SET NULL,
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
            FOREIGN KEY (id_departamento) REFERENCES departamentos(id_departamento) ON DELETE CASCADE,
            INDEX idx_sensor (id_sensor),
            INDEX idx_usuario (id_usuario),
            INDEX idx_departamento (id_departamento),
            INDEX idx_fecha (fecha_hora),
            INDEX idx_tipo (tipo_evento),
            INDEX idx_resultado (resultado)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($conn->query($sql)) {
            $tables_created++;
            $response['details'][] = "✓ Tabla 'eventos_acceso' creada";
        }
    }

    // 5. Tabla estado_barrera
    if (!in_array('estado_barrera', $existing_tables)) {
        $sql = "CREATE TABLE IF NOT EXISTS estado_barrera (
            id_estado INT AUTO_INCREMENT PRIMARY KEY,
            estado_actual ENUM('ABIERTA', 'CERRADA') DEFAULT 'CERRADA',
            ultimo_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            id_usuario_responsable INT,
            accion ENUM('APERTURA_AUTOMATICA', 'APERTURA_MANUAL', 'CIERRE_AUTOMATICO', 'CIERRE_MANUAL'),
            FOREIGN KEY (id_usuario_responsable) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($conn->query($sql)) {
            $tables_created++;
            $response['details'][] = "✓ Tabla 'estado_barrera' creada";

            // Insertar estado inicial
            $conn->query("INSERT INTO estado_barrera (estado_actual, accion) VALUES ('CERRADA', 'CIERRE_AUTOMATICO')");
        }
    }

    // 6. Tabla password_resets
    if (!in_array('password_resets', $existing_tables)) {
        $sql = "CREATE TABLE IF NOT EXISTS password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            code VARCHAR(6) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            expires_at TIMESTAMP NULL,
            usado BOOLEAN DEFAULT FALSE,
            INDEX idx_email (email),
            INDEX idx_code (code),
            INDEX idx_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($conn->query($sql)) {
            $tables_created++;
            $response['details'][] = "✓ Tabla 'password_resets' creada";
        }
    }

    // Verificar si hay datos de ejemplo
    $result_dept = $conn->query("SELECT COUNT(*) as count FROM departamentos");
    $count_dept = $result_dept->fetch_assoc()['count'];

    if ($count_dept == 0) {
        // Insertar datos de ejemplo
        $conn->query("INSERT INTO departamentos (numero, torre, piso, condominio) VALUES
            ('101', 'A', 1, 'Condominio HomePass'),
            ('102', 'A', 1, 'Condominio HomePass'),
            ('201', 'B', 2, 'Condominio HomePass')");

        // Contraseña: password123
        $password_hash = password_hash('password123', PASSWORD_DEFAULT);

        $conn->query("INSERT INTO usuarios (id_departamento, nombre, apellido, email, password_hash, telefono, rut, rol, estado) VALUES
            (1, 'Savka', 'Carvajal', 'savka.carvajal@inacapmail.cl', '$password_hash', '912345678', '12345678-9', 'ADMINISTRADOR', 'ACTIVO'),
            (1, 'Dante', 'Gutierrez', 'dante.gutierrez@inacapmail.cl', '$password_hash', '987654321', '98765432-1', 'OPERADOR', 'ACTIVO'),
            (2, 'Admin', 'Depto102', 'admin102@homepass.cl', '$password_hash', '911111111', '11111111-1', 'ADMINISTRADOR', 'ACTIVO')");

        $conn->query("INSERT INTO sensores (id_departamento, id_usuario, codigo_sensor, nombre_sensor, tipo, estado) VALUES
            (1, 1, 'A1B2C3D4', 'Llavero Principal Savka', 'LLAVERO', 'ACTIVO'),
            (1, 1, 'E5F6G7H8', 'Tarjeta Savka', 'TARJETA', 'ACTIVO'),
            (1, 2, 'I9J0K1L2', 'Llavero Dante', 'LLAVERO', 'ACTIVO'),
            (2, 3, 'M3N4O5P6', 'Tarjeta Admin 102', 'TARJETA', 'ACTIVO')");

        $response['details'][] = "✓ Datos de ejemplo insertados (3 departamentos, 3 usuarios, 4 sensores)";
        $response['details'][] = "Usuario de prueba: savka.carvajal@inacapmail.cl / password123";
    }

    $response['success'] = true;
    $response['message'] = "Setup completado exitosamente. Tablas creadas: $tables_created";
    $response['tables_count'] = count($existing_tables) + $tables_created;

    $conn->close();

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>


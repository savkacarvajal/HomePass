-- =====================================================
-- SCRIPT DE CREACIÓN DE BASE DE DATOS - HOMEPASS IoT
-- Sistema de Control de Acceso para Condominio
-- =====================================================

-- Usar la base de datos
USE homepass_db;

-- =====================================================
-- 1. TABLA: DEPARTAMENTOS
-- =====================================================
CREATE TABLE IF NOT EXISTS departamentos (
    id_departamento INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(10) NOT NULL,
    torre VARCHAR(10),
    piso INT,
    condominio VARCHAR(100),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_numero (numero),
    INDEX idx_torre (torre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. TABLA: USUARIOS
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. TABLA: SENSORES (Tarjetas/Llaveros RFID)
-- =====================================================
CREATE TABLE IF NOT EXISTS sensores (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. TABLA: EVENTOS_ACCESO
-- =====================================================
CREATE TABLE IF NOT EXISTS eventos_acceso (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 5. TABLA: ESTADO_BARRERA (Para control en tiempo real)
-- =====================================================
CREATE TABLE IF NOT EXISTS estado_barrera (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    estado_actual ENUM('ABIERTA', 'CERRADA') DEFAULT 'CERRADA',
    ultimo_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    id_usuario_responsable INT,
    accion ENUM('APERTURA_AUTOMATICA', 'APERTURA_MANUAL', 'CIERRE_AUTOMATICO', 'CIERRE_MANUAL'),
    FOREIGN KEY (id_usuario_responsable) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar estado inicial de la barrera
INSERT INTO estado_barrera (estado_actual, accion)
VALUES ('CERRADA', 'CIERRE_AUTOMATICO')
ON DUPLICATE KEY UPDATE estado_actual = estado_actual;

-- =====================================================
-- 6. TABLA: PASSWORD_RESETS (Recuperación de contraseña)
-- =====================================================
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    usado BOOLEAN DEFAULT FALSE,
    INDEX idx_email (email),
    INDEX idx_code (code),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DATOS DE EJEMPLO PARA PRUEBAS
-- =====================================================

-- Insertar departamentos de ejemplo
INSERT INTO departamentos (numero, torre, piso, condominio) VALUES
('101', 'A', 1, 'Condominio HomePass'),
('102', 'A', 1, 'Condominio HomePass'),
('201', 'B', 2, 'Condominio HomePass');

-- Insertar usuarios de ejemplo (contraseña: "password123" - hash MD5 para ejemplo)
INSERT INTO usuarios (id_departamento, nombre, apellido, email, password_hash, telefono, rut, rol, estado) VALUES
(1, 'Savka', 'Carvajal', 'savka.carvajal@inacapmail.cl', MD5('password123'), '912345678', '12345678-9', 'ADMINISTRADOR', 'ACTIVO'),
(1, 'Dante', 'Gutierrez', 'dante.gutierrez@inacapmail.cl', MD5('password123'), '987654321', '98765432-1', 'OPERADOR', 'ACTIVO'),
(2, 'Admin', 'Depto102', 'admin102@homepass.cl', MD5('password123'), '911111111', '11111111-1', 'ADMINISTRADOR', 'ACTIVO');

-- Insertar sensores de ejemplo
INSERT INTO sensores (id_departamento, id_usuario, codigo_sensor, nombre_sensor, tipo, estado) VALUES
(1, 1, 'A1B2C3D4', 'Llavero Principal Savka', 'LLAVERO', 'ACTIVO'),
(1, 1, 'E5F6G7H8', 'Tarjeta Savka', 'TARJETA', 'ACTIVO'),
(1, 2, 'I9J0K1L2', 'Llavero Dante', 'LLAVERO', 'ACTIVO'),
(2, 3, 'M3N4O5P6', 'Tarjeta Admin 102', 'TARJETA', 'ACTIVO');

-- =====================================================
-- EVENTOS PARA LIMPIEZA AUTOMÁTICA
-- =====================================================

-- Eliminar códigos de recuperación expirados cada 5 minutos
DELIMITER $$
CREATE EVENT IF NOT EXISTS limpiar_codigos_expirados
ON SCHEDULE EVERY 5 MINUTE
DO
BEGIN
    DELETE FROM password_resets
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE)
    OR usado = TRUE;
END$$
DELIMITER ;

-- Habilitar el Event Scheduler
SET GLOBAL event_scheduler = ON;

-- =====================================================
-- VISTAS ÚTILES
-- =====================================================

-- Vista de usuarios con su departamento
CREATE OR REPLACE VIEW vista_usuarios_departamentos AS
SELECT
    u.id_usuario,
    u.nombre,
    u.apellido,
    u.email,
    u.rol,
    u.estado AS estado_usuario,
    d.numero AS numero_departamento,
    d.torre,
    d.piso,
    COUNT(s.id_sensor) AS cantidad_sensores
FROM usuarios u
INNER JOIN departamentos d ON u.id_departamento = d.id_departamento
LEFT JOIN sensores s ON u.id_usuario = s.id_usuario AND s.estado = 'ACTIVO'
GROUP BY u.id_usuario, u.nombre, u.apellido, u.email, u.rol, u.estado, d.numero, d.torre, d.piso;

-- Vista de sensores activos
CREATE OR REPLACE VIEW vista_sensores_activos AS
SELECT
    s.id_sensor,
    s.codigo_sensor,
    s.nombre_sensor,
    s.tipo,
    s.estado,
    u.nombre AS nombre_usuario,
    u.apellido AS apellido_usuario,
    d.numero AS numero_departamento,
    d.torre
FROM sensores s
INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
INNER JOIN departamentos d ON s.id_departamento = d.id_departamento
WHERE s.estado = 'ACTIVO';

-- Vista de eventos recientes
CREATE OR REPLACE VIEW vista_eventos_recientes AS
SELECT
    e.id_evento,
    e.tipo_evento,
    e.resultado,
    e.fecha_hora,
    s.codigo_sensor,
    s.nombre_sensor,
    s.tipo AS tipo_sensor,
    u.nombre AS nombre_usuario,
    u.apellido AS apellido_usuario,
    d.numero AS numero_departamento,
    d.torre
FROM eventos_acceso e
LEFT JOIN sensores s ON e.id_sensor = s.id_sensor
LEFT JOIN usuarios u ON e.id_usuario = u.id_usuario
INNER JOIN departamentos d ON e.id_departamento = d.id_departamento
ORDER BY e.fecha_hora DESC
LIMIT 100;

-- =====================================================
-- PROCEDIMIENTOS ALMACENADOS ÚTILES
-- =====================================================

-- Procedimiento para registrar un evento de acceso
DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS registrar_evento_acceso(
    IN p_codigo_sensor VARCHAR(50),
    IN p_tipo_evento VARCHAR(50),
    IN p_resultado VARCHAR(20),
    IN p_observaciones TEXT
)
BEGIN
    DECLARE v_id_sensor INT;
    DECLARE v_id_usuario INT;
    DECLARE v_id_departamento INT;

    -- Buscar el sensor
    SELECT id_sensor, id_usuario, id_departamento
    INTO v_id_sensor, v_id_usuario, v_id_departamento
    FROM sensores
    WHERE codigo_sensor = p_codigo_sensor
    LIMIT 1;

    -- Si no se encuentra el sensor, registrar con NULL
    IF v_id_sensor IS NULL THEN
        SET v_id_departamento = 1; -- Departamento por defecto
    END IF;

    -- Insertar el evento
    INSERT INTO eventos_acceso (
        id_sensor,
        id_usuario,
        id_departamento,
        tipo_evento,
        resultado,
        codigo_sensor,
        observaciones
    ) VALUES (
        v_id_sensor,
        v_id_usuario,
        v_id_departamento,
        p_tipo_evento,
        p_resultado,
        p_codigo_sensor,
        p_observaciones
    );
END$$
DELIMITER ;

-- =====================================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =====================================================

-- Índice compuesto para búsquedas frecuentes
CREATE INDEX idx_sensor_departamento_estado ON sensores(id_departamento, estado);
CREATE INDEX idx_usuario_departamento_rol ON usuarios(id_departamento, rol);
CREATE INDEX idx_evento_fecha_tipo ON eventos_acceso(fecha_hora, tipo_evento);

-- =====================================================
-- PERMISOS Y SEGURIDAD
-- =====================================================

-- Nota: Estos comandos se ejecutan como root en MySQL
-- GRANT SELECT, INSERT, UPDATE, DELETE ON homepass_db.* TO 'homepass_user'@'%';
-- FLUSH PRIVILEGES;

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================

SELECT 'Base de datos HomePass IoT creada exitosamente' AS mensaje;
SELECT 'Total de tablas creadas: 6' AS info;
SELECT 'Total de vistas creadas: 3' AS info;
SELECT 'Total de procedimientos: 1' AS info;


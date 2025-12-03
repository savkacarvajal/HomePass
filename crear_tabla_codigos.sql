-- crear_tabla_codigos.sql
-- Script SQL para crear la tabla password_resets (para códigos de recuperación)

CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(5) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Opcional: Crear un evento para limpiar códigos expirados automáticamente
-- (requiere que el Event Scheduler esté habilitado en MySQL)
DELIMITER $$

CREATE EVENT IF NOT EXISTS limpiar_codigos_expirados
ON SCHEDULE EVERY 5 MINUTE
DO
BEGIN
    DELETE FROM password_resets
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 2 MINUTE);
END$$

DELIMITER ;

-- Para habilitar el Event Scheduler (ejecutar como administrador):
-- SET GLOBAL event_scheduler = ON;


-- =====================================================
-- CONFIGURAR ZONA HORARIA DE CHILE EN LA BASE DE DATOS
-- Ejecutar este script para sincronizar todas las fechas
-- =====================================================

-- Establecer zona horaria del servidor MySQL
SET GLOBAL time_zone = '-03:00';
SET time_zone = '-03:00';

-- Verificar configuración actual
SELECT @@global.time_zone, @@session.time_zone, NOW() as fecha_actual_chile;

-- =====================================================
-- NOTA: Este script configura la hora de Chile (UTC-3)
-- Todas las tablas con TIMESTAMP usarán automáticamente
-- esta zona horaria para:
-- - usuarios.fecha_creacion
-- - sensores.fecha_alta
-- - eventos_acceso.fecha_hora
-- - password_resets.created_at
-- =====================================================

-- Para verificar que las fechas están correctas:
SELECT
    'Últimos usuarios' as tabla,
    nombre,
    apellido,
    email,
    fecha_creacion,
    DATE_FORMAT(fecha_creacion, '%d-%m-%Y %H:%i:%s') as fecha_formato_chile
FROM usuarios
ORDER BY fecha_creacion DESC
LIMIT 5;

SELECT
    'Últimos eventos' as tabla,
    tipo_evento,
    resultado,
    fecha_hora,
    DATE_FORMAT(fecha_hora, '%d-%m-%Y %H:%i:%s') as fecha_formato_chile
FROM eventos_acceso
ORDER BY fecha_hora DESC
LIMIT 5;

-- =====================================================
-- SI LAS FECHAS ESTÁN INCORRECTAS, EJECUTAR ESTO:
-- =====================================================

-- Esto ajustará todas las fechas existentes a la zona horaria de Chile
-- (Solo si fueron guardadas en UTC u otra zona)

-- DESCOMENTAR SI ES NECESARIO AJUSTAR FECHAS ANTIGUAS:
/*
UPDATE usuarios
SET fecha_creacion = DATE_ADD(fecha_creacion, INTERVAL -3 HOUR)
WHERE fecha_creacion > '2025-01-01';

UPDATE eventos_acceso
SET fecha_hora = DATE_ADD(fecha_hora, INTERVAL -3 HOUR)
WHERE fecha_hora > '2025-01-01';

UPDATE sensores
SET fecha_alta = DATE_ADD(fecha_alta, INTERVAL -3 HOUR)
WHERE fecha_alta > '2025-01-01';

UPDATE password_resets
SET created_at = DATE_ADD(created_at, INTERVAL -3 HOUR)
WHERE created_at > '2025-01-01';
*/

-- =====================================================
-- VERIFICACIÓN FINAL
-- =====================================================
SELECT
    NOW() as hora_actual_mysql,
    CONVERT_TZ(NOW(), @@session.time_zone, 'America/Santiago') as hora_chile,
    DATE_FORMAT(NOW(), '%d-%m-%Y %H:%i:%s') as formato_legible;


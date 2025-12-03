-- =====================================================
-- SCRIPT DE VERIFICACIÓN - HOMEPASS IoT
-- Verifica que todas las tablas y datos estén correctos
-- =====================================================

USE homepass_db;

-- Verificar tablas creadas
SELECT 'VERIFICACIÓN DE TABLAS' AS paso;
SHOW TABLES;

-- Verificar departamentos
SELECT 'DEPARTAMENTOS CREADOS' AS paso;
SELECT * FROM departamentos;

-- Verificar usuarios
SELECT 'USUARIOS CREADOS' AS paso;
SELECT id_usuario, nombre, apellido, email, rol, estado
FROM usuarios;

-- Verificar sensores
SELECT 'SENSORES CREADOS' AS paso;
SELECT id_sensor, codigo_sensor, nombre_sensor, tipo, estado
FROM sensores;

-- Verificar estado de la barrera
SELECT 'ESTADO DE LA BARRERA' AS paso;
SELECT * FROM estado_barrera;

-- Verificar vistas
SELECT 'VISTAS CREADAS' AS paso;
SHOW FULL TABLES WHERE Table_type = 'VIEW';

-- Verificar procedimientos
SELECT 'PROCEDIMIENTOS CREADOS' AS paso;
SHOW PROCEDURE STATUS WHERE Db = 'homepass_db';

-- Verificar eventos
SELECT 'EVENTOS PROGRAMADOS' AS paso;
SHOW EVENTS;

-- Prueba de la vista de usuarios con departamentos
SELECT 'VISTA: Usuarios con Departamentos' AS paso;
SELECT * FROM vista_usuarios_departamentos;

-- Prueba de la vista de sensores activos
SELECT 'VISTA: Sensores Activos' AS paso;
SELECT * FROM vista_sensores_activos;

-- Verificar índices de la tabla sensores
SELECT 'ÍNDICES DE LA TABLA SENSORES' AS paso;
SHOW INDEX FROM sensores;

SELECT '✅ VERIFICACIÓN COMPLETA' AS resultado;


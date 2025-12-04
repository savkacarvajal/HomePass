-- =====================================================
-- SOLUCIÓN RÁPIDA: Ver/Generar código de recuperación
-- Email: savka.carvajal@inacapmail.cl
-- =====================================================

-- PASO 1: Verificar si el usuario existe
SELECT
    '=== VERIFICACIÓN DE USUARIO ===' as info;

SELECT
    id_usuario,
    CONCAT(nombre, ' ', apellido) as nombre_completo,
    email,
    estado,
    CASE
        WHEN estado = 'ACTIVO' THEN '✅ Usuario activo'
        ELSE '❌ Usuario inactivo - ejecuta: UPDATE usuarios SET estado = "ACTIVO" WHERE email = "savka.carvajal@inacapmail.cl"'
    END as resultado
FROM usuarios
WHERE email = 'savka.carvajal@inacapmail.cl';

-- Si no muestra ningún resultado, el usuario NO existe
-- Debes registrarte primero desde la app

-- =====================================================
-- PASO 2: Ver si hay códigos generados
-- =====================================================

SELECT
    '=== CÓDIGOS DE RECUPERACIÓN ===' as info;

SELECT
    code as '📧 CÓDIGO (Úsalo en la app)',
    created_at as fecha_creacion,
    expires_at as fecha_expiracion,
    TIMESTAMPDIFF(MINUTE, created_at, NOW()) as minutos_transcurridos,
    CASE
        WHEN TIMESTAMPDIFF(MINUTE, created_at, NOW()) <= 15 THEN '✅ CÓDIGO VÁLIDO - Úsalo ahora'
        ELSE '❌ Código expirado - Genera uno nuevo abajo'
    END as estado
FROM password_resets
WHERE email = 'savka.carvajal@inacapmail.cl'
ORDER BY created_at DESC
LIMIT 3;

-- =====================================================
-- PASO 3A: Si NO hay código o está expirado, genera uno nuevo
-- =====================================================

-- Eliminar códigos antiguos (opcional - para limpiar)
DELETE FROM password_resets
WHERE email = 'savka.carvajal@inacapmail.cl';

-- Generar nuevo código de 5 dígitos
INSERT INTO password_resets (email, code, created_at, expires_at)
VALUES (
    'savka.carvajal@inacapmail.cl',
    LPAD(FLOOR(RAND() * 100000), 5, '0'),  -- Genera número aleatorio de 5 dígitos
    NOW(),
    DATE_ADD(NOW(), INTERVAL 15 MINUTE)
);

-- Ver el código que acabas de generar
SELECT
    '=== 📧 TU CÓDIGO DE RECUPERACIÓN ===' as info;

SELECT
    code as '🔑 CÓDIGO (Cópialo)',
    '✅ VÁLIDO POR 15 MINUTOS' as validez,
    'Ingrésalo en la app para cambiar tu contraseña' as instrucciones
FROM password_resets
WHERE email = 'savka.carvajal@inacapmail.cl'
ORDER BY created_at DESC
LIMIT 1;

-- =====================================================
-- PASO 3B: ALTERNATIVA - Ver el código más reciente sin generar uno nuevo
-- =====================================================

-- Ejecuta solo esto si ya solicitaste el código desde la app
SELECT
    code as 'TU CÓDIGO',
    TIMESTAMPDIFF(MINUTE, created_at, NOW()) as minutos_transcurridos,
    15 - TIMESTAMPDIFF(MINUTE, created_at, NOW()) as minutos_restantes,
    CASE
        WHEN TIMESTAMPDIFF(MINUTE, created_at, NOW()) <= 15 THEN '✅ VÁLIDO'
        ELSE '❌ EXPIRADO'
    END as estado
FROM password_resets
WHERE email = 'savka.carvajal@inacapmail.cl'
ORDER BY created_at DESC
LIMIT 1;

-- =====================================================
-- PASO 4: Validar el código (Simulación de lo que hace la app)
-- =====================================================

-- Sustituye '12345' por el código que obtuviste arriba
SET @codigo_a_validar = '12345';  -- ⚠️ CAMBIA ESTO por tu código real

SELECT
    CASE
        WHEN COUNT(*) > 0 AND TIMESTAMPDIFF(MINUTE, MAX(created_at), NOW()) <= 15
        THEN CONCAT('✅ Código ', @codigo_a_validar, ' es VÁLIDO')
        WHEN COUNT(*) > 0 AND TIMESTAMPDIFF(MINUTE, MAX(created_at), NOW()) > 15
        THEN CONCAT('❌ Código ', @codigo_a_validar, ' está EXPIRADO')
        ELSE CONCAT('❌ Código ', @codigo_a_validar, ' NO EXISTE')
    END as resultado
FROM password_resets
WHERE email = 'savka.carvajal@inacapmail.cl'
  AND code = @codigo_a_validar;

-- =====================================================
-- MÉTODO ALTERNATIVO: Cambiar contraseña directamente
-- =====================================================

-- Si necesitas cambiar la contraseña sin usar código:

-- PASO 1: Generar hash de la contraseña en PHP
-- Ejecuta en terminal o crea archivo temporal:
-- php -r "echo password_hash('Test1234!', PASSWORD_DEFAULT);"

-- PASO 2: Actualizar con el hash generado
-- UPDATE usuarios
-- SET password_hash = '$2y$10$EL_HASH_QUE_GENERASTE_EN_PHP'
-- WHERE email = 'savka.carvajal@inacapmail.cl';

-- EJEMPLO con contraseña "Test1234!" (este hash es de ejemplo, genera el tuyo):
-- UPDATE usuarios
-- SET password_hash = '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890'
-- WHERE email = 'savka.carvajal@inacapmail.cl';

-- =====================================================
-- VERIFICACIÓN FINAL
-- =====================================================

SELECT
    '=== RESUMEN FINAL ===' as info;

SELECT
    u.email,
    u.estado as usuario_estado,
    COUNT(pr.code) as codigos_disponibles,
    MAX(pr.code) as codigo_mas_reciente,
    MAX(pr.created_at) as fecha_ultimo_codigo,
    CASE
        WHEN COUNT(pr.code) > 0 AND TIMESTAMPDIFF(MINUTE, MAX(pr.created_at), NOW()) <= 15
        THEN '✅ Tienes un código válido'
        WHEN COUNT(pr.code) > 0 AND TIMESTAMPDIFF(MINUTE, MAX(pr.created_at), NOW()) > 15
        THEN '⚠️ Código expirado - genera uno nuevo'
        ELSE '❌ No hay códigos - genera uno'
    END as estado
FROM usuarios u
LEFT JOIN password_resets pr ON u.email = pr.email
WHERE u.email = 'savka.carvajal@inacapmail.cl'
GROUP BY u.email, u.estado;

-- =====================================================
-- INSTRUCCIONES FINALES
-- =====================================================

/*
RESUMEN DE PASOS:

1. Ejecuta PASO 1 para verificar que el usuario existe

2. Ejecuta PASO 2 para ver si ya hay un código

3A. Si NO hay código o está expirado:
    - Ejecuta PASO 3A para generar uno nuevo
    - Copia el código de 5 dígitos

3B. Si YA solicitaste código desde la app:
    - Ejecuta PASO 3B para ver el código

4. Ve a la app:
   - Ingresa el código de 5 dígitos
   - Crea tu nueva contraseña
   - Haz login con la nueva contraseña

✅ LISTO!

NOTA: Los códigos expiran en 15 minutos.
Si pasó más tiempo, genera uno nuevo con PASO 3A.
*/

-- =====================================================
-- TROUBLESHOOTING
-- =====================================================

-- ❌ Error: "Usuario no existe"
-- SELECT * FROM usuarios;  -- Ver todos los usuarios
-- Solución: Regístrate desde la app primero

-- ❌ Error: "Código expirado"
-- Solución: Ejecuta PASO 3A para generar uno nuevo

-- ❌ Error: "Contraseña no se actualiza"
-- Solución: Verifica que el hash sea correcto o usa el código

-- ❌ Email no llega
-- Solución: Usa este script para ver el código directamente desde la BD

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================

-- 🎯 EJECUCIÓN RÁPIDA (Copia y pega esto):

-- Ver código existente o generar uno nuevo:
DELETE FROM password_resets WHERE email = 'savka.carvajal@inacapmail.cl';
INSERT INTO password_resets (email, code, created_at, expires_at)
VALUES ('savka.carvajal@inacapmail.cl', LPAD(FLOOR(RAND() * 100000), 5, '0'), NOW(), DATE_ADD(NOW(), INTERVAL 15 MINUTE));
SELECT code as 'TU CÓDIGO' FROM password_resets WHERE email = 'savka.carvajal@inacapmail.cl' ORDER BY created_at DESC LIMIT 1;


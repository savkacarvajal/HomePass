-- =====================================================
-- VERIFICAR Y CONFIGURAR USUARIO ADMINISTRADOR
-- HomePass IoT - Para usar la app del celular como admin
-- =====================================================

-- 1. Ver todos los usuarios actuales y sus roles
SELECT
    id_usuario,
    CONCAT(nombre, ' ', apellido) as nombre_completo,
    email,
    rol,
    estado,
    id_departamento,
    fecha_creacion
FROM usuarios
ORDER BY id_departamento, fecha_creacion;

-- 2. Ver cuántos usuarios hay por departamento y quién es admin
SELECT
    d.numero as departamento,
    d.torre,
    d.piso,
    COUNT(u.id_usuario) as total_usuarios,
    SUM(CASE WHEN u.rol = 'ADMINISTRADOR' THEN 1 ELSE 0 END) as admins,
    SUM(CASE WHEN u.rol = 'OPERADOR' THEN 1 ELSE 0 END) as operadores
FROM departamentos d
LEFT JOIN usuarios u ON d.id_departamento = u.id_departamento
GROUP BY d.id_departamento
ORDER BY d.numero;

-- =====================================================
-- OPCIÓN A: YA TIENES USUARIO - Solo verificar
-- =====================================================

-- Verificar si tu email ya existe
SELECT
    id_usuario,
    CONCAT(nombre, ' ', apellido) as nombre_completo,
    email,
    rol,
    estado,
    'Password está cifrado (bcrypt)' as password_info
FROM usuarios
WHERE email = 'savka.carvajal@inacapmail.cl';

-- Si existe, verificar que sea ADMINISTRADOR
-- Si no lo es, actualizarlo:
UPDATE usuarios
SET rol = 'ADMINISTRADOR'
WHERE email = 'savka.carvajal@inacapmail.cl';

-- =====================================================
-- OPCIÓN B: CREAR USUARIO ADMIN DESDE CERO
-- =====================================================

-- Primero, asegúrate de tener un departamento
-- Ver departamentos disponibles:
SELECT * FROM departamentos LIMIT 5;

-- Si no hay departamentos, crear uno de ejemplo:
INSERT INTO departamentos (numero, torre, piso, condominio, direccion)
VALUES (101, 'A', 1, 'Condominio HomePass', 'Calle Principal 123')
ON DUPLICATE KEY UPDATE numero = numero; -- No hace nada si ya existe

-- ⚠️ IMPORTANTE: NO INSERTES USUARIO DIRECTAMENTE CON PASSWORD
-- La contraseña debe ser hasheada con password_hash() de PHP
-- Usa la app o register.php para registrar usuarios

-- Para probar desde la app:
-- 1. Abre la app en tu celular
-- 2. Ve a "Registrarse"
-- 3. Llena los datos:
--    - Nombres: Savka
--    - Apellidos: Carvajal
--    - Email: savka.carvajal@inacapmail.cl
--    - Contraseña: Test1234! (o la que quieras)
--    - Departamento: 101
-- 4. El sistema automáticamente te asignará rol ADMINISTRADOR
--    (porque eres el primer usuario de ese departamento)

-- =====================================================
-- VERIFICACIÓN FINAL
-- =====================================================

-- Ver el usuario que acabas de crear/actualizar
SELECT
    id_usuario,
    nombre,
    apellido,
    email,
    rol,
    estado,
    id_departamento,
    fecha_creacion,
    CASE
        WHEN rol = 'ADMINISTRADOR' THEN '✅ Puede gestionar usuarios'
        ELSE '⚠️ Solo lectura'
    END as permisos
FROM usuarios
WHERE email = 'savka.carvajal@inacapmail.cl';

-- =====================================================
-- CAMBIAR CONTRASEÑA (Si olvidaste la contraseña)
-- =====================================================

-- Opción 1: Usar la app
-- 1. En Login, presiona "¿Olvidaste tu contraseña?"
-- 2. Ingresa tu email
-- 3. Recibirás un código
-- 4. Ingresa el código y crea nueva contraseña

-- Opción 2: Ver el código desde la base de datos (solo para pruebas)
SELECT
    email,
    code as codigo,
    created_at as creado,
    TIMESTAMPDIFF(MINUTE, created_at, NOW()) as minutos_desde_creacion,
    CASE
        WHEN TIMESTAMPDIFF(MINUTE, created_at, NOW()) <= 15 THEN '✅ Código válido'
        ELSE '❌ Código expirado'
    END as estado
FROM password_resets
WHERE email = 'savka.carvajal@inacapmail.cl'
ORDER BY created_at DESC
LIMIT 1;

-- Opción 3: Crear contraseña manualmente (SOLO PARA EMERGENCIAS)
-- ⚠️ No recomendado - Mejor usa la app

-- Para crear hash de "Test1234!" en PHP:
-- password_hash("Test1234!", PASSWORD_DEFAULT)
-- Resultado: $2y$10$...hash_largo...

-- Actualizar con hash (ejemplo con contraseña "Test1234!")
-- UPDATE usuarios
-- SET password_hash = '$2y$10$hash_que_generes_en_php'
-- WHERE email = 'savka.carvajal@inacapmail.cl';

-- =====================================================
-- DATOS PARA LOGIN EN LA APP
-- =====================================================

-- Una vez que tengas el usuario creado, usa:
-- Email: savka.carvajal@inacapmail.cl
-- Contraseña: La que configuraste al registrarte

-- El login verificará:
-- ✅ Email existe
-- ✅ Estado = 'ACTIVO'
-- ✅ Contraseña coincide (password_verify)
-- ✅ Retorna datos del usuario con rol

-- =====================================================
-- TROUBLESHOOTING
-- =====================================================

-- ❌ Error: "Email ya registrado"
SELECT * FROM usuarios WHERE email = 'savka.carvajal@inacapmail.cl';
-- Solución: Usa ese email para login, o elimínalo y crea uno nuevo

-- ❌ Error: "Contraseña incorrecta"
-- Solución: Usa recuperar contraseña en la app

-- ❌ Error: "Usuario no existe"
-- Solución: Registra el usuario desde la app

-- ❌ Error: "Usuario inactivo"
UPDATE usuarios SET estado = 'ACTIVO' WHERE email = 'savka.carvajal@inacapmail.cl';

-- =====================================================
-- RESUMEN - PASOS PARA USAR LA APP COMO ADMIN
-- =====================================================

/*
PASO 1: Verificar/Crear Usuario
   → Ejecutar esta consulta para ver si existe:
     SELECT * FROM usuarios WHERE email = 'savka.carvajal@inacapmail.cl';

   → Si NO existe:
     - Abre la app
     - Ve a "Registrarse"
     - Llena los datos
     - Automáticamente serás ADMINISTRADOR (primer usuario del depto)

   → Si SÍ existe pero no es ADMIN:
     UPDATE usuarios SET rol = 'ADMINISTRADOR' WHERE email = 'tu_email';

PASO 2: Login en la App
   → Abre la app
   → Ingresa email y contraseña
   → Presiona "INGRESAR"
   → ✅ Deberías entrar al menú principal

PASO 3: Verificar Permisos
   → En el menú principal, ve a "Gestión de Usuarios"
   → Como ADMIN, puedes:
     ✅ Ver todos los usuarios
     ✅ Agregar usuarios
     ✅ Modificar usuarios
     ✅ Eliminar usuarios

PASO 4: Si olvidaste la contraseña
   → En Login, presiona "¿Olvidaste tu contraseña?"
   → Ingresa tu email
   → Recibirás código
   → Crea nueva contraseña
   → Login nuevamente
*/

-- =====================================================
-- FIN DEL SCRIPT
-- =====================================================


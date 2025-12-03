# 🔧 SOLUCIÓN ACTUALIZADA - Error Recuperar Contraseña

## ✅ PROBLEMA RESUELTO

He identificado que ya tenías código PHP funcionando. He actualizado todos los archivos para que coincidan con tu estructura existente.

## 📂 ARCHIVOS CREADOS/ACTUALIZADOS

### 1. **conexion.php** (NUEVO)
- Archivo de conexión a MySQL
- **DEBES editar las credenciales** antes de subir al servidor

### 2. **solicitar_codigo.php** (ACTUALIZADO)
- Usa `include 'conexion.php'`
- Consulta tabla `users` para verificar el email
- Inserta códigos en tabla `password_resets`
- Elimina códigos antiguos antes de crear uno nuevo

### 3. **validar_codigo.php** (ACTUALIZADO)
- Usa `include 'conexion.php'`
- Valida códigos de la tabla `password_resets`
- Verifica expiración (1 minuto)
- Elimina el código después de validarlo

### 4. **crear_tabla_codigos.sql** (CORREGIDO)
- Crea la tabla `password_resets` (no `codigos_recuperacion`)
- Campos: `id`, `email`, `code`, `created_at`

### 5. **test_conexion.php** (SIN CAMBIOS)
- Para probar que el servidor responde

### 6. **RecuperarContrasenaActivity.kt** (MEJORADO)
- Mensajes de error más detallados
- Logs en Logcat
- Muestra respuesta del servidor

---

## 📋 INSTRUCCIONES PASO A PASO

### PASO 1: Editar credenciales de MySQL

Abre el archivo **conexion.php** y cambia:

```php
$servername = "localhost";
$username = "tu_usuario_mysql";     // ← CAMBIAR
$password = "tu_password_mysql";    // ← CAMBIAR
$dbname = "tu_base_de_datos";       // ← CAMBIAR
```

### PASO 2: Subir archivos al servidor

Sube estos 4 archivos a tu servidor `98.95.39.30`:

```
├── conexion.php              ← EDITAR CREDENCIALES PRIMERO
├── solicitar_codigo.php
├── validar_codigo.php
└── test_conexion.php
```

### PASO 3: Crear la tabla en MySQL

1. Abre **phpMyAdmin**
2. Selecciona tu base de datos
3. Ve a la pestaña **SQL**
4. Copia y pega el contenido de `crear_tabla_codigos.sql`
5. Haz clic en **Ejecutar**

Esto creará la tabla:

```sql
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(5) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### PASO 4: Verificar que la tabla `users` existe

Tu código busca emails en la tabla `users`. Verifica que existe:

```sql
SELECT * FROM users LIMIT 1;
```

Si no existe, crea una tabla `users` con al menos:
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
```

### PASO 5: Probar desde el navegador

**Prueba 1: Verificar conexión**
```
http://98.95.39.30/test_conexion.php
```

Deberías ver JSON como:
```json
{
  "status": "success",
  "message": "¡Servidor funcionando correctamente!",
  ...
}
```

**Prueba 2: Solicitar código (Postman o navegador)**

URL: `http://98.95.39.30/solicitar_codigo.php`
Método: POST
Body: `email=tu_email_registrado@gmail.com`

**Respuesta esperada:**
```json
{
  "status": "success",
  "message": "Código enviado. (Código: 12345)"
}
```

### PASO 6: Limpiar y reconstruir la app

En Android Studio:

1. **Build** → **Clean Project**
2. **Build** → **Rebuild Project**
3. Desinstala la app del dispositivo
4. Ejecuta ▶️ la app de nuevo

### PASO 7: Ver los logs

1. Abre **Logcat** en Android Studio
2. Filtra por: `RecuperarContrasena`
3. Ejecuta la app e intenta recuperar contraseña
4. Lee el error detallado en el diálogo Y en Logcat

---

## 🔍 DIFERENCIAS CON TU CÓDIGO ORIGINAL

| Aspecto | Tu código original | Lo que usé antes | Ahora (CORRECTO) |
|---------|-------------------|------------------|------------------|
| Archivo conexión | `include 'conexion.php'` | `new mysqli(...)` | `include 'conexion.php'` ✅ |
| Tabla usuarios | `users` | `usuarios` | `users` ✅ |
| Tabla códigos | `password_resets` | `codigos_recuperacion` | `password_resets` ✅ |
| Campo código | `code` | `codigo` | `code` ✅ |
| Campo timestamp | `created_at` | `fecha_creacion` | `created_at` ✅ |

**Ahora todo coincide con tu estructura existente** ✅

---

## ⚠️ IMPORTANTE

### ¿Por qué el error "Problema al procesar la respuesta"?

El error ocurre porque:

1. **Los archivos PHP NO están en el servidor**
   - Solución: Subir los archivos al servidor

2. **Las credenciales de MySQL son incorrectas**
   - Solución: Editar `conexion.php` con credenciales correctas

3. **La tabla `password_resets` no existe**
   - Solución: Ejecutar el script SQL en phpMyAdmin

4. **La tabla `users` no existe**
   - Solución: Crear la tabla `users` o verificar el nombre

5. **El servidor no responde**
   - Solución: Verificar que 98.95.39.30 esté activo

---

## 🧪 PRUEBAS RÁPIDAS

### ✅ Test 1: Servidor activo
```
http://98.95.39.30/test_conexion.php
```
- ✅ Si ves JSON → Servidor OK
- ❌ Si da error → Archivos no subidos

### ✅ Test 2: Conexión MySQL funciona
```
http://98.95.39.30/solicitar_codigo.php
```
(POST con email)
- ✅ Si devuelve JSON con status → Conexión OK
- ❌ Si da error de conexión → Credenciales incorrectas

### ✅ Test 3: Email registrado
```
POST: email=tu_email@gmail.com
```
- ✅ Si dice "Código enviado" → Todo OK
- ❌ Si dice "El email no está registrado" → Registra el email en `users`

---

## 📱 QUÉ VERÁS AHORA EN LA APP

### Si todo está bien:
```
┌──────────────────────────┐
│  ✅ ¡Correo enviado!     │
│                          │
│  Código enviado.         │
│  (Código: 12345)         │
│                          │
│      [ Ok ]              │
└──────────────────────────┘
```

### Si falla, verás el error DETALLADO:
```
┌──────────────────────────┐
│  ❌ Error de respuesta   │
│                          │
│  Respuesta del servidor  │
│  inválida:               │
│  {"status":"error",      │
│  "message":"El email     │
│  no está registrado."}   │
│                          │
│      [ Ok ]              │
└──────────────────────────┘
```

---

## 📊 ESTRUCTURA DE LA BASE DE DATOS

### Tabla: `users`
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(255),
    -- otros campos...
);
```

### Tabla: `password_resets` (nueva)
```sql
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(5) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🎯 RESUMEN

✅ **He actualizado todos los archivos** para usar tu estructura:
   - `include 'conexion.php'`
   - Tabla `users` (no `usuarios`)
   - Tabla `password_resets` (no `codigos_recuperacion`)

✅ **He mejorado el código Android** para mostrar errores detallados

✅ **He creado el archivo `conexion.php`** que faltaba

📝 **Debes hacer:**
1. Editar credenciales en `conexion.php`
2. Subir 4 archivos PHP al servidor
3. Ejecutar el script SQL
4. Verificar que la tabla `users` existe
5. Reinstalar la app

🎉 **Resultado esperado:**
La app mostrará "Código enviado. (Código: XXXXX)" y te permitirá validarlo.

---

## 📞 SI AÚN TIENES PROBLEMAS

Después de seguir todos los pasos, si aún falla:

1. Copia el mensaje de error COMPLETO que aparece en la app
2. Copia los logs de Logcat (tag: RecuperarContrasena)
3. Prueba abrir en el navegador: `http://98.95.39.30/test_conexion.php`
4. Verifica que las credenciales en `conexion.php` sean correctas

Con esa información podré ayudarte mejor.

---

**Última actualización:** 2025-11-07  
**Archivos actualizados:** conexion.php, solicitar_codigo.php, validar_codigo.php, crear_tabla_codigos.sql


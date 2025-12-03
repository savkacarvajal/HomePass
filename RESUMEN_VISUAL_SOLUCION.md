# 📋 RESUMEN VISUAL: Problema y Solución de Recuperar Contraseña

## 🔴 PROBLEMA ACTUAL

```
┌─────────────────┐         POST email         ┌──────────────┐
│                 │ ─────────────────────────▶ │              │
│   APP ANDROID   │                            │   SERVIDOR   │
│                 │ ◀───────────────────────── │              │
└─────────────────┘     Content-Length: 0      └──────────────┘
                        (RESPUESTA VACÍA)
                              ❌

App recibe: ""
Error: "end of input at character 0 of"
```

### ¿Por qué pasa esto?

El archivo `solicitar_codigo.php` en el servidor está **INCOMPLETO**:

```php
// ❌ CÓDIGO INCOMPLETO (lo que tienes ahora)
if ($email_exists) {
    $code = str_pad(random_int(10000, 99999), 5, '0', STR_PAD_LEFT);
    // ... (Lógica de DELETE y INSERT con sentencias preparadas) ...
    
    // Asumiendo éxito de la inserción:  ← ¡NO HACE NADA!
    $response['status'] = 'success';
}
```

Como no hay lógica de INSERT real, probablemente hay un **error PHP** que impide la ejecución y no se genera ninguna salida JSON.

---

## 🟢 SOLUCIÓN

```
┌─────────────────┐         POST email         ┌──────────────┐
│                 │ ─────────────────────────▶ │              │
│   APP ANDROID   │                            │   SERVIDOR   │
│                 │                            │   PHP NUEVO  │
│                 │ ◀───────────────────────── │              │
└─────────────────┘   JSON: {status: success}  └──────────────┘
                              ✅

App recibe: {"status":"success","message":"...","code":"12345"}
```

### Archivos NUEVOS creados:

1. ✅ `solicitar_codigo_NUEVO.php` - **COMPLETO** con INSERT real
2. ✅ `validar_codigo_NUEVO.php` - **COMPLETO** con validación real
3. ✅ `subir_archivos_nuevos.bat` - Script para subirlos automáticamente
4. ✅ `test_recuperar.bat` - Script para probar que funcione

---

## 📊 FLUJO COMPLETO DE RECUPERACIÓN

```
┌─────────────────────────────────────────────────────────────────┐
│                    1. SOLICITAR CÓDIGO                          │
└─────────────────────────────────────────────────────────────────┘
    
    Usuario ingresa email: "test@example.com"
           ▼
    App envía POST a solicitar_codigo.php
           ▼
    PHP verifica si email existe en tabla 'users'
           ▼
    PHP genera código aleatorio: "12345"
           ▼
    PHP guarda en tabla 'password_resets':
        - email: test@example.com
        - code: 12345
        - created_at: 2025-11-07 15:58:00
           ▼
    PHP responde: {"status":"success","message":"...DEBUG: 12345"}
           ▼
    App muestra campos para ingresar código
    
┌─────────────────────────────────────────────────────────────────┐
│                    2. VALIDAR CÓDIGO                            │
└─────────────────────────────────────────────────────────────────┘
    
    Usuario ingresa código: "12345"
           ▼
    App envía POST a validar_codigo.php con email + code
           ▼
    PHP busca en 'password_resets' WHERE email = ?
           ▼
    PHP verifica:
        ✓ ¿Existe el código? → Sí
        ✓ ¿Coincide? → Sí (12345 == 12345)
        ✓ ¿No expiró? → Sí (menos de 60 seg)
           ▼
    PHP elimina el código usado
           ▼
    PHP responde: {"status":"success","message":"Código validado"}
           ▼
    App redirige a CrearContrasenaActivity

┌─────────────────────────────────────────────────────────────────┐
│                    3. CREAR NUEVA CONTRASEÑA                    │
└─────────────────────────────────────────────────────────────────┘
    
    Usuario ingresa nueva contraseña
           ▼
    App envía a cambiar_contrasena.php
           ▼
    PHP actualiza password en tabla 'users'
           ▼
    Usuario puede iniciar sesión con nueva contraseña
```

---

## 🗃️ ESTRUCTURA DE BASE DE DATOS REQUERIDA

### Tabla: `users` (ya existe)
```sql
┌────┬────────────────────────┬──────────────────────┐
│ id │        email           │      password        │
├────┼────────────────────────┼──────────────────────┤
│ 1  │ salvador@gmail.com     │ $2y$10$abc...       │
│ 3  │ dante.gutierrez@...    │ $2y$10$def...       │
│ 4  │ luna@gmail.com         │ $2y$10$ghi...       │
└────┴────────────────────────┴──────────────────────┘
```

### Tabla: `password_resets` (debe crearse)
```sql
┌────┬────────────────────────┬──────┬─────────────────────┐
│ id │        email           │ code │     created_at      │
├────┼────────────────────────┼──────┼─────────────────────┤
│ 1  │ test@example.com       │ 12345│ 2025-11-07 15:58:00 │
└────┴────────────────────────┴──────┴─────────────────────┘
           ▲                     ▲              ▲
           │                     │              │
     Se busca aquí        Se compara aquí   Se verifica
                                            que no hayan
                                            pasado 60 seg
```

**IMPORTANTE:** Esta tabla se limpia automáticamente:
- Cada código se elimina después de usarse
- Códigos expiran después de 60 segundos

---

## 🛠️ PASOS PARA SOLUCIONAR (RESUMEN)

### 1️⃣ Crear la tabla (si no existe)
```bash
ssh -i tu-clave.pem ec2-user@98.95.39.30
mysql -u root -p pnkcl_iot < crear_tabla_codigos.sql
```

### 2️⃣ Subir archivos PHP nuevos
```batch
# Edita subir_archivos_nuevos.bat con tu clave SSH
# Luego ejecuta:
subir_archivos_nuevos.bat
```

### 3️⃣ Probar que funcione
```batch
test_recuperar.bat
```

Deberías ver:
```json
{"status":"success","message":"Si el email está registrado... (DEBUG: 12345)"}
```

### 4️⃣ Probar en la app
1. Abre la app
2. Ve a "Recuperar Contraseña"
3. Ingresa un email que exista en la BD
4. Deberías ver el mensaje con el código de DEBUG
5. Ingresa el código
6. Deberías poder crear una nueva contraseña

---

## 📝 COMPARACIÓN: ANTES vs DESPUÉS

### ❌ ANTES (Archivo incompleto)
```
curl POST → solicitar_codigo.php
     ▼
  PHP error (no hay INSERT)
     ▼
  Content-Length: 0
     ▼
  App recibe: ""
     ▼
  Error: "end of input at character 0"
```

### ✅ DESPUÉS (Archivo completo)
```
curl POST → solicitar_codigo.php
     ▼
  PHP ejecuta INSERT correctamente
     ▼
  Content-Length: 87
     ▼
  App recibe: {"status":"success",...}
     ▼
  ¡Funciona! 🎉
```

---

## 🎯 CHECKLIST VISUAL

```
📋 Preparación del Servidor
  ├─ [ ] Tabla password_resets creada
  ├─ [ ] solicitar_codigo.php actualizado
  ├─ [ ] validar_codigo.php actualizado
  └─ [ ] Permisos 644 establecidos

🧪 Pruebas
  ├─ [ ] curl test_conexion.php → JSON OK
  ├─ [ ] curl solicitar_codigo.php → JSON OK (no Content-Length: 0)
  └─ [ ] curl validar_codigo.php → JSON OK

📱 Prueba en App
  ├─ [ ] Solicitar código → Muestra campos de validación
  ├─ [ ] Validar código → Redirige a crear contraseña
  └─ [ ] Cambiar contraseña → Login exitoso
```

---

## 🆘 SI TODAVÍA NO FUNCIONA

### Ver logs en tiempo real:
```bash
ssh -i tu-clave.pem ec2-user@98.95.39.30
sudo tail -f /var/log/php-fpm/error.log /var/log/httpd/error_log
```

### Probar manualmente en el servidor:
```bash
ssh -i tu-clave.pem ec2-user@98.95.39.30
cd /var/www/html
php -l solicitar_codigo.php  # Verificar sintaxis
cat solicitar_codigo.php | head -50  # Ver contenido
```

### Habilitar errores temporalmente:
```php
// En solicitar_codigo.php, cambiar:
error_reporting(0);  →  error_reporting(E_ALL);
ini_set('display_errors', '0');  →  ini_set('display_errors', '1');
```

---

## ✅ CONFIRMACIÓN FINAL

Cuando todo funcione, verás esto en LogCat:

```
D/RecuperarContrasena: Respuesta del servidor: {"status":"success","message":"Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 12345)"}
```

Y la app mostrará:
```
┌─────────────────────────────────────────┐
│         ¡Correo enviado!  ✓             │
│                                         │
│  Si el email está registrado, se ha     │
│  enviado un código... (DEBUG: 12345)    │
│                                         │
│              [ OK ]                      │
└─────────────────────────────────────────┘
```

¡Eso significa que está funcionando! 🎉


# 🔧 SOLUCIÓN DEFINITIVA: Error "end of input at character 0"

## 🔍 DIAGNÓSTICO DEL PROBLEMA

El error **"end of input at character 0"** significa que tu app Android está recibiendo una respuesta **VACÍA** del servidor (Content-Length: 0).

### Causa Principal
Los archivos PHP en el servidor están **incompletos** o tienen errores que impiden generar el JSON de respuesta.

---

## ✅ SOLUCIÓN PASO A PASO

### PASO 1: Verificar la tabla en la Base de Datos

**IMPORTANTE:** La tabla `password_resets` debe existir en tu BD.

1. Conéctate a tu BD MySQL:
```bash
ssh -i tu-clave.pem ec2-user@98.95.39.30
mysql -u root -p
```

2. Ejecuta estos comandos:
```sql
USE pnkcl_iot;

-- Crear la tabla si no existe
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(5) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Verificar que existe
SHOW TABLES LIKE 'password_resets';

-- Ver estructura
DESCRIBE password_resets;
```

### PASO 2: Subir los archivos PHP NUEVOS al servidor

He creado versiones **COMPLETAS Y FUNCIONALES** de los archivos PHP:

- `solicitar_codigo_NUEVO.php` → reemplaza `solicitar_codigo.php`
- `validar_codigo_NUEVO.php` → reemplaza `validar_codigo.php`

**Opción A: Usar el script batch automático**

1. Edita `subir_archivos_nuevos.bat` y actualiza la ruta de tu clave SSH:
```batch
set KEY_PATH=C:\Users\savka\.ssh\TU-CLAVE.pem
```

2. Ejecuta el script:
```batch
subir_archivos_nuevos.bat
```

**Opción B: Subir manualmente con SCP**

```bash
# Solicitar código
scp -i tu-clave.pem solicitar_codigo_NUEVO.php ec2-user@98.95.39.30:/var/www/html/solicitar_codigo.php

# Validar código
scp -i tu-clave.pem validar_codigo_NUEVO.php ec2-user@98.95.39.30:/var/www/html/validar_codigo.php

# Establecer permisos
ssh -i tu-clave.pem ec2-user@98.95.39.30 "sudo chmod 644 /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php"
```

### PASO 3: Probar desde el terminal

Ejecuta `test_recuperar.bat` o manualmente:

```bash
# Test 1: Conexión
curl -v http://98.95.39.30/test_conexion.php

# Test 2: Solicitar código
curl -v -X POST -d "email=test@example.com" http://98.95.39.30/solicitar_codigo.php

# Deberías ver algo como:
# {"status":"success","message":"Si el email está registrado... (DEBUG: 12345)"}
```

**✅ SI VES UN JSON → Funciona correctamente**
**❌ SI VES Content-Length: 0 → Hay un problema en el PHP**

### PASO 4: Verificar errores en el servidor (si persiste el problema)

```bash
ssh -i tu-clave.pem ec2-user@98.95.39.30

# Ver errores de PHP-FPM
sudo tail -f /var/log/php-fpm/error.log

# Ver errores de Apache
sudo tail -f /var/log/httpd/error_log

# Ver si el archivo existe y tiene contenido
cat /var/www/html/solicitar_codigo.php | head -20
```

---

## 🔍 DIFERENCIAS CLAVE DE LOS ARCHIVOS NUEVOS

### ❌ Archivo VIEJO (incompleto):
```php
if ($email_exists) {
    // Generar código seguro y ejecutar SQL (Omitido por concisión, asume éxito)
    $code = str_pad(random_int(10000, 99999), 5, '0', STR_PAD_LEFT);
    // ... (Lógica de DELETE y INSERT con sentencias preparadas) ...
    
    // Asumiendo éxito de la inserción:
    $response['status'] = 'success';
    $response['message'] = $GENERIC_SUCCESS_MESSAGE . ' (DEBUG: ' . $code . ')';
}
```

### ✅ Archivo NUEVO (completo):
```php
if ($email_exists) {
    // 2. Generar código seguro de 5 dígitos
    $code = str_pad(random_int(10000, 99999), 5, '0', STR_PAD_LEFT);

    // 3. Eliminar códigos anteriores del mismo email
    $stmt_delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    if ($stmt_delete) {
        $stmt_delete->bind_param("s", $email);
        $stmt_delete->execute();
        $stmt_delete->close();
    }

    // 4. Insertar el nuevo código
    $stmt_insert = $conn->prepare("INSERT INTO password_resets (email, code, created_at) VALUES (?, ?, NOW())");
    
    if (!$stmt_insert) {
        $response['message'] = 'Error SQL al insertar código: ' . $conn->error;
        ob_end_clean();
        echo json_encode($response);
        $conn->close();
        exit;
    }

    $stmt_insert->bind_param("ss", $email, $code);
    
    if ($stmt_insert->execute()) {
        $stmt_insert->close();
        $response['status'] = 'success';
        $response['message'] = $GENERIC_SUCCESS_MESSAGE . ' (DEBUG: ' . $code . ')';
    } else {
        $response['message'] = 'Error al guardar el código: ' . $stmt_insert->error;
        $stmt_insert->close();
    }
}
```

---

## 🎯 CHECKLIST FINAL

- [ ] Tabla `password_resets` creada en la BD
- [ ] Archivo `solicitar_codigo_NUEVO.php` subido como `solicitar_codigo.php`
- [ ] Archivo `validar_codigo_NUEVO.php` subido como `validar_codigo.php`
- [ ] Permisos 644 establecidos en los archivos
- [ ] Prueba con curl muestra JSON válido (no Content-Length: 0)
- [ ] App Android recibe respuesta correcta

---

## 🐛 SI TODAVÍA NO FUNCIONA

### Verifica que el email exista en la BD

```sql
USE pnkcl_iot;
SELECT id, email FROM users WHERE email = 'test@example.com';

-- Si no existe, créalo:
INSERT INTO users (email, password) VALUES ('test@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz');
```

### Habilita errores temporalmente

Edita `/var/www/html/solicitar_codigo.php` en el servidor:

```php
// Cambiar:
error_reporting(0);
ini_set('display_errors', '0');

// Por:
error_reporting(E_ALL);
ini_set('display_errors', '1');
```

Luego prueba de nuevo con curl y verás los errores específicos.

---

## 📱 SOBRE LA APP ANDROID

La app está **CORRECTA**. El código en `RecuperarContrasenaActivity.kt` maneja bien:

- ✅ Validación de email
- ✅ Solicitud POST con Volley
- ✅ Manejo de errores de red
- ✅ Parsing de JSON
- ✅ Logging detallado

El problema es **100% del lado del servidor** (PHP vacío).

---

## 🎉 RESULTADO ESPERADO

Después de aplicar estos cambios:

1. La app enviará el email
2. El servidor generará un código de 5 dígitos
3. El servidor responderá con JSON válido
4. La app mostrará los campos para ingresar el código
5. El usuario podrá validar el código y cambiar su contraseña

**Tiempo de expiración:** 60 segundos (configurable en el PHP)


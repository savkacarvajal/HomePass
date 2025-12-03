# DIAGNÓSTICO Y SOLUCIÓN - Error de Recuperación de Contraseña

## 🔍 PROBLEMA IDENTIFICADO

El error **"Error de respuesta - Problema al procesar la respuesta"** que aparece en tu aplicación ocurre porque:

### Causas principales:

1. **El servidor no responde correctamente** (98.95.39.30)
   - Los archivos PHP no existen en el servidor
   - El servidor está caído o no responde
   - El servidor devuelve HTML (error 404/500) en lugar de JSON

2. **Formato de respuesta incorrecto**
   - La respuesta del servidor no es JSON válido
   - Faltan los campos "status" y "message" en la respuesta

3. **Problema de conectividad**
   - La app no puede alcanzar el servidor
   - Firewall o configuración de red bloqueando la conexión

## ✅ SOLUCIONES IMPLEMENTADAS

### 1. Mejoras en el código Android

He modificado `RecuperarContrasenaActivity.kt` para:

✅ **Agregar logs detallados** que muestran:
   - La respuesta completa del servidor
   - El error exacto que ocurre
   - El código HTTP de respuesta

✅ **Mensajes de error más informativos** que muestran:
   - Los primeros 200 caracteres de la respuesta del servidor
   - El código de estado HTTP
   - El mensaje de error específico

### 2. Archivos PHP creados

He creado 3 archivos que debes subir a tu servidor:

#### 📄 `solicitar_codigo.php`
- Genera un código de 5 dígitos
- Lo guarda en la base de datos con timestamp
- Responde con JSON válido

#### 📄 `validar_codigo.php`
- Valida el código ingresado
- Verifica que no haya expirado (1 minuto)
- Elimina el código después de validarlo

#### 📄 `crear_tabla_codigos.sql`
- Script SQL para crear la tabla necesaria
- Incluye evento automático para limpiar códigos expirados

## 📋 PASOS PARA SOLUCIONAR

### Paso 1: Subir archivos PHP al servidor

1. Abre un cliente FTP o el panel de control de tu servidor
2. Sube estos archivos a la raíz del servidor (donde está tu dominio):
   - `solicitar_codigo.php`
   - `validar_codigo.php`

3. Edita ambos archivos PHP y cambia las credenciales de base de datos:
   ```php
   $servername = "localhost";
   $username = "tu_usuario_mysql";    // ← Cambiar
   $password = "tu_password_mysql";   // ← Cambiar
   $dbname = "tu_base_de_datos";      // ← Cambiar
   ```

### Paso 2: Crear tabla en la base de datos

1. Accede a phpMyAdmin o tu gestor de base de datos
2. Ejecuta el script SQL que está en `crear_tabla_codigos.sql`
3. Verifica que la tabla `codigos_recuperacion` se haya creado

### Paso 3: Verificar que la tabla usuarios existe

La tabla `usuarios` debe tener al menos estos campos:
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    -- otros campos...
);
```

### Paso 4: Probar los endpoints

Abre el navegador y prueba:

**Probar solicitar_codigo.php:**
```
http://98.95.39.30/solicitar_codigo.php
```
Debería devolver un JSON como:
```json
{"status":"error","message":"Método no permitido"}
```

**Si devuelve esto, el archivo funciona correctamente.**

### Paso 5: Reinstalar la app

1. Desinstala la app actual del dispositivo
2. En Android Studio, ejecuta: **Build > Clean Project**
3. Luego: **Build > Rebuild Project**
4. Instala la app de nuevo

### Paso 6: Ver los logs

Cuando la app falle, abre el **Logcat** en Android Studio y busca:
```
RecuperarContrasena
```

Los logs te mostrarán exactamente qué está devolviendo el servidor.

## 🔧 PRUEBAS ADICIONALES

### Probar desde Postman o curl

**Windows (PowerShell):**
```powershell
Invoke-WebRequest -Uri "http://98.95.39.30/solicitar_codigo.php" -Method POST -Body @{email="test@test.com"} | Select-Object Content
```

**Linux/Mac:**
```bash
curl -X POST http://98.95.39.30/solicitar_codigo.php -d "email=test@test.com"
```

Deberías recibir:
```json
{"status":"success","message":"Código enviado..."}
```
o
```json
{"status":"error","message":"El email no está registrado"}
```

## ⚠️ VERIFICACIONES IMPORTANTES

### ✓ Checklist antes de probar:

- [ ] Los archivos PHP están en el servidor
- [ ] Las credenciales de MySQL están correctas en los PHP
- [ ] La tabla `codigos_recuperacion` existe
- [ ] La tabla `usuarios` existe y tiene emails registrados
- [ ] El servidor permite conexiones HTTP (no solo HTTPS)
- [ ] El AndroidManifest.xml tiene `android:usesCleartextTraffic="true"` ✓ (ya lo tienes)
- [ ] El dispositivo/emulador tiene acceso a Internet

## 🆘 SI AÚN NO FUNCIONA

### Opción A: Servidor de prueba local

Si no puedes acceder al servidor 98.95.39.30, puedes crear un servidor local:

1. Instala XAMPP/WAMPP
2. Copia los archivos PHP a `C:\xampp\htdocs\`
3. En tu código Android, cambia la URL a:
   ```kotlin
   val url = "http://10.0.2.2/solicitar_codigo.php"  // Para emulador
   // o
   val url = "http://TU_IP_LOCAL/solicitar_codigo.php"  // Para dispositivo real
   ```

### Opción B: Usar un servidor de prueba gratuito

Sube los archivos a:
- InfinityFree
- 000webhost
- Hostinger (plan gratuito)

Y cambia la URL en el código Android.

## 📱 CÓMO VER EL ERROR EXACTO

Después de las modificaciones, cuando presiones "RECUPERAR" y falle:

1. El diálogo de error ahora mostrará:
   - La respuesta exacta del servidor (primeros 200 caracteres)
   - El tipo de error específico
   - El código HTTP si aplica

2. En Logcat verás líneas como:
   ```
   D/RecuperarContrasena: Respuesta del servidor: <html>...
   E/RecuperarContrasena: Error al parsear JSON: org.json.JSONException...
   ```

Esto te dirá exactamente qué está mal.

## 📞 NECESITAS MÁS AYUDA

Si después de seguir todos estos pasos aún tienes problemas:

1. Copia el mensaje completo del error que aparece en el diálogo
2. Copia los logs del Logcat que digan "RecuperarContrasena"
3. Verifica que puedas acceder a http://98.95.39.30 desde tu navegador

---

**Archivos creados en este proyecto:**
- ✅ `solicitar_codigo.php` - Backend para solicitar código
- ✅ `validar_codigo.php` - Backend para validar código
- ✅ `crear_tabla_codigos.sql` - Script SQL para crear tabla
- ✅ `RecuperarContrasenaActivity.kt` - Modificado con mejor manejo de errores


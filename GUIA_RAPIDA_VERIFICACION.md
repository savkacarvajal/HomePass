# ⚡ GUÍA RÁPIDA - Verificación Paso a Paso

## 🔧 CHECKLIST DE VERIFICACIÓN

Marca cada paso que completes:

### ☑️ SERVIDOR

- [ ] El servidor 98.95.39.30 está activo y responde
- [ ] Puedo acceder a http://98.95.39.30 desde mi navegador
- [ ] Tengo acceso FTP o cPanel al servidor
- [ ] Subí `solicitar_codigo.php` al servidor
- [ ] Subí `validar_codigo.php` al servidor
- [ ] Subí `test_conexion.php` al servidor
- [ ] Los archivos están en la raíz (no en subcarpetas)

**Prueba:** Abre http://98.95.39.30/test_conexion.php
- ✅ Si ves JSON → Continúa
- ❌ Si ves error 404 → Los archivos no están subidos correctamente

---

### ☑️ BASE DE DATOS

- [ ] Tengo acceso a phpMyAdmin o gestor MySQL
- [ ] Creé la tabla `codigos_recuperacion` con el script SQL
- [ ] La tabla `usuarios` existe y tiene datos
- [ ] Verifiqué que hay al menos un email registrado
- [ ] Edité las credenciales MySQL en `solicitar_codigo.php`
- [ ] Edité las credenciales MySQL en `validar_codigo.php`
- [ ] Las credenciales son correctas (usuario, password, nombre BD)

**Prueba:** Ejecuta en phpMyAdmin:
```sql
SELECT * FROM usuarios LIMIT 1;
SELECT * FROM codigos_recuperacion;
```
- ✅ Si devuelve resultados → Continúa
- ❌ Si da error → Revisa los nombres de las tablas

---

### ☑️ APLICACIÓN ANDROID

- [ ] Limpié el proyecto: Build → Clean Project
- [ ] Reconstruí el proyecto: Build → Rebuild Project
- [ ] Desinstalé la app anterior del dispositivo
- [ ] Instalé la nueva versión de la app
- [ ] El dispositivo/emulador tiene Internet activo
- [ ] Abrí Logcat en Android Studio
- [ ] Configuré el filtro "RecuperarContrasena" en Logcat

**Prueba:** Ejecuta la app e intenta recuperar contraseña
- ✅ Si funciona → ¡Listo! 🎉
- ❌ Si falla → Lee el error detallado y los logs

---

## 🧪 PRUEBAS MANUALES

### Prueba 1: Servidor responde
```
URL: http://98.95.39.30/test_conexion.php
Método: GET
Esperado: JSON con status="success"
```

### Prueba 2: Solicitar código (email no existe)
```
URL: http://98.95.39.30/solicitar_codigo.php
Método: POST
Body: email=noexit@gmail.com
Esperado: {"status":"error","message":"El email no está registrado"}
```

### Prueba 3: Solicitar código (email existe)
```
URL: http://98.95.39.30/solicitar_codigo.php
Método: POST
Body: email=TU_EMAIL_REGISTRADO@gmail.com
Esperado: {"status":"success","message":"Código enviado..."}
```

### Prueba 4: Validar código (código incorrecto)
```
URL: http://98.95.39.30/validar_codigo.php
Método: POST
Body: email=TU_EMAIL@gmail.com&code=00000
Esperado: {"status":"error","message":"Código incorrecto"}
```

---

## 📱 CÓMO INTERPRETAR LOS ERRORES

### Error 1: "Error de conexión"
```
No se pudo conectar al servidor.
Código HTTP: null
```
**Causa:** El servidor no responde o no hay Internet
**Solución:**
1. Verifica Internet en el dispositivo
2. Prueba abrir http://98.95.39.30 en el navegador del celular
3. Verifica que el servidor esté activo

---

### Error 2: "Respuesta del servidor inválida: <!DOCTYPE html>"
```
Respuesta del servidor inválida:
<!DOCTYPE html><html><head><title>404 Not Found</title>...
```
**Causa:** El archivo PHP no existe en el servidor
**Solución:**
1. Verifica que subiste los archivos PHP
2. Verifica que están en la raíz, no en subcarpetas
3. Verifica los permisos de los archivos (644 o 755)

---

### Error 3: "Código HTTP: 500"
```
Código HTTP: 500
Respuesta: {"status":"error","message":"Error del servidor: ..."}
```
**Causa:** Error en el código PHP o en la base de datos
**Solución:**
1. Lee el mensaje de error completo
2. Si dice "Access denied" → Credenciales MySQL incorrectas
3. Si dice "Table doesn't exist" → Falta crear la tabla
4. Si dice "Unknown column" → La estructura de la tabla está mal

---

### Error 4: "org.json.JSONException"
```
Error: org.json.JSONException: No value for status
```
**Causa:** La respuesta no tiene el formato JSON correcto
**Solución:**
1. Los archivos PHP deben empezar con `<?php` (sin espacios antes)
2. Verifica que no haya echo o print antes del JSON
3. Verifica que el header Content-Type esté configurado

---

## 🎯 SOLUCIONES RÁPIDAS

### Problema: No tengo acceso al servidor 98.95.39.30

**Solución A: Usar servidor local (XAMPP)**
1. Instala XAMPP
2. Copia los archivos PHP a `C:\xampp\htdocs\`
3. En el código Android, cambia:
   ```kotlin
   val url = "http://10.0.2.2/solicitar_codigo.php"  // Para emulador
   ```
4. Si usas dispositivo real, usa tu IP local:
   ```kotlin
   val url = "http://192.168.1.XXX/solicitar_codigo.php"
   ```

**Solución B: Usar hosting gratuito**
1. Crea cuenta en 000webhost, InfinityFree o similar
2. Sube los archivos PHP
3. Crea la base de datos MySQL
4. En el código Android, cambia la URL a tu nuevo dominio

---

### Problema: La respuesta tarda mucho

**Causa:** El servidor está lento o la consulta es pesada

**Solución:**
Aumenta el timeout en Volley:
```kotlin
val stringRequest = object : StringRequest(Method.POST, url, ...) {
    override fun getRetryPolicy() = DefaultRetryPolicy(
        10000,  // 10 segundos timeout
        DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
        DefaultRetryPolicy.DEFAULT_BACKOFF_MULT
    )
}
```

---

### Problema: El código siempre dice "expirado"

**Causa:** La hora del servidor está mal configurada

**Solución:**
En los archivos PHP, agrega al inicio:
```php
date_default_timezone_set('America/Mexico_City');  // Tu zona horaria
```

---

## 📊 COMANDOS ÚTILES

### Ver logs en tiempo real (Android Studio)
```
Logcat → Filtro: RecuperarContrasena
```

### Limpiar proyecto
```
Build → Clean Project → Build → Rebuild Project
```

### Ver respuesta del servidor (navegador)
```
http://98.95.39.30/test_conexion.php
```

### Probar con PowerShell
```powershell
Invoke-RestMethod -Uri "http://98.95.39.30/test_conexion.php" -Method GET
```

### Ver tablas MySQL (phpMyAdmin)
```sql
SHOW TABLES;
DESCRIBE usuarios;
DESCRIBE codigos_recuperacion;
```

---

## ✅ SEÑALES DE QUE TODO FUNCIONA

1. **Servidor:** `test_conexion.php` devuelve JSON
2. **Base de datos:** Las consultas SQL no dan error
3. **App:** Presionas RECUPERAR y sale "¡Correo enviado!"
4. **Logs:** No hay errores en Logcat
5. **Validación:** Ingresas el código y te lleva a crear contraseña

---

## 🆘 ÚLTIMO RECURSO

Si NADA funciona, envíame:

1. ✉️ Screenshot del error completo en la app
2. 📄 Los logs de Logcat (tag: RecuperarContrasena)
3. 🌐 La respuesta de `http://98.95.39.30/test_conexion.php`
4. 💾 Screenshot de phpMyAdmin mostrando las tablas

Con esa información podré darte una solución más específica.

---

**Última actualización:** 2025-11-07
**Archivos modificados:** RecuperarContrasenaActivity.kt
**Archivos creados:** solicitar_codigo.php, validar_codigo.php, crear_tabla_codigos.sql, test_conexion.php


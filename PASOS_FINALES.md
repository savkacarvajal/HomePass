# ✅ ARCHIVOS YA SUBIDOS - PASOS FINALES

## 🎉 CONFIRMACIÓN

Veo en tu captura de WinSCP que los archivos YA ESTÁN en el servidor:

```
✅ /var/www/html/solicitar_codigo.php (3 KB) - 07-11-2025 12:38:33
✅ /var/www/html/validar_codigo.php (5 KB) - 07-11-2025 11:25:33
```

**¡Bien hecho!** Ya completaste la parte de WinSCP.

---

## 🧪 AHORA: PROBAR QUE FUNCIONEN

### OPCIÓN 1: Desde tu PC Windows (cmd.exe)

1. **Abre cmd.exe** (NO PowerShell):
   - Presiona `Win + R`
   - Escribe `cmd` y Enter

2. **Ejecuta este comando:**
   ```batch
   cd C:\Users\savka\AndroidStudioProjects\Test
   PROBAR_SERVIDOR.bat
   ```

3. **Verás la prueba automática** de los 3 archivos PHP

---

### OPCIÓN 2: Prueba manual con curl desde cmd.exe

Abre **cmd.exe** y ejecuta:

```batch
curl -s -X POST -d "email=luna@gmail.com" http://98.95.39.30/solicitar_codigo.php
```

**Respuesta esperada:**
```json
{"status":"success","message":"Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 12345)"}
```

---

### OPCIÓN 3: Desde el servidor SSH (si tienes acceso)

Si puedes conectarte al servidor con PuTTY usando tu archivo `.ppk`:

```bash
# Conectar
# (Abre PuTTY, carga tu .ppk, conecta a 98.95.39.30)

# Probar
curl -X POST -d "email=luna@gmail.com" http://localhost/solicitar_codigo.php
```

---

## 🔍 VERIFICAR CONTENIDO DE LOS ARCHIVOS

Si quieres asegurarte de que los archivos que subiste son los correctos:

### En WinSCP:
1. Click derecho en `solicitar_codigo.php`
2. Selecciona **"Edit"**
3. Verifica que empiece con:
   ```php
   <?php
   // solicitar_codigo.php - VERSIÓN COMPLETA Y FUNCIONAL
   
   // Limpiar TODA la salida previa y buffer
   while (ob_get_level()) {
       ob_end_clean();
   }
   ```

Si NO empieza así, **necesitas reemplazarlo** con los archivos `*_NUEVO.php` que creé.

---

## ⚠️ SI LOS ARCHIVOS NO SON LOS CORRECTOS

Veo en tu WinSCP que hay varios archivos:
- `solicitar_codigo.php` (3 KB) ← **Este debe ser el correcto**
- `solicitar_codigo_fix.php` (0 KB) ← **Vacío, no sirve**
- `validar_codigo.php` (5 KB) ← **Este debe ser el correcto**
- `validar_codigo_NUEVO...` (5 KB) ← **Este es el que creé**

### Si necesitas reemplazarlos:

1. **En tu PC**, busca estos archivos:
   ```
   C:\Users\savka\AndroidStudioProjects\Test\
     ├─ solicitar_codigo_NUEVO.php (4 KB)
     └─ validar_codigo_NUEVO.php (4 KB)
   ```

2. **En WinSCP**:
   - Arrastra `solicitar_codigo_NUEVO.php` desde PC → Servidor
   - Suelta sobre `solicitar_codigo.php` (sobrescribir)
   - Confirma **"Overwrite"** o **"Yes to All"**
   - Repite con `validar_codigo_NUEVO.php` → `validar_codigo.php`

---

## 📋 CHECKLIST DE VERIFICACIÓN

```
[✓] Archivos subidos al servidor con WinSCP
[ ] Prueba ejecutada desde cmd.exe
[ ] Respuesta JSON recibida (no vacía)
[ ] App Android probada
[ ] Recuperar contraseña funciona ✅
```

---

## 🎯 PRUEBA EN LA APP ANDROID

Si las pruebas con curl funcionan, **ahora prueba en la app**:

1. Abre tu app Android
2. Ve a **"Recuperar Contraseña"**
3. Ingresa el email: `luna@gmail.com` (o cualquier email que exista en tu BD)
4. Click en **"Solicitar Código"**

**Deberías ver:**
```
┌──────────────────────────────┐
│   ¡Correo enviado! ✓         │
│                              │
│  Si el email está registrado │
│  ... (DEBUG: 12345)          │
│                              │
│        [  OK  ]               │
└──────────────────────────────┘
```

5. Aparecen los campos para ingresar código
6. Ingresa el código que viste (ej: 12345)
7. Click en **"Validar Código"**
8. Te lleva a crear nueva contraseña

**¡Eso significa que FUNCIONA!** 🎉

---

## 🆘 SI NO FUNCIONA

### Problema 1: curl no muestra nada o muestra HTML

**Solución:** Los archivos que subiste no son los correctos.

**Acción:**
1. Edita en WinSCP el archivo `solicitar_codigo.php`
2. Verifica que tenga la lógica completa de INSERT
3. Si está incompleto, reemplázalo con `solicitar_codigo_NUEVO.php`

### Problema 2: "Código incorrecto" o "No se encontró código"

**Solución:** Falta la tabla `password_resets` en la BD.

**Acción:** Conéctate al servidor y ejecuta:
```bash
mysql -u root -p  # Contraseña: Admin12345
USE pnkcl_iot;
SHOW TABLES LIKE 'password_resets';

# Si no existe, créala con el archivo crear_tabla_codigos.sql
```

### Problema 3: App muestra "Error de conexión"

**Solución:** Verifica que el servidor esté accesible.

**Acción:**
```batch
curl http://98.95.39.30/test_conexion.php
```

Debería devolver JSON con "status":"success".

---

## 📊 RESUMEN DE ESTADO

```
Estado de archivos:
  ✅ solicitar_codigo.php en servidor (3 KB)
  ✅ validar_codigo.php en servidor (5 KB)
  ✅ Archivos locales NUEVO.php disponibles (4 KB cada uno)

Siguiente paso:
  🧪 Ejecutar PROBAR_SERVIDOR.bat desde cmd.exe
  
Si prueba OK:
  📱 Probar en app Android
  
Si prueba FALLA:
  🔄 Reemplazar con archivos *_NUEVO.php
```

---

## 🚀 EJECUTA AHORA

**Desde cmd.exe (NO PowerShell):**

```batch
cd C:\Users\savka\AndroidStudioProjects\Test
PROBAR_SERVIDOR.bat
```

O directamente:

```batch
curl -s -X POST -d "email=luna@gmail.com" http://98.95.39.30/solicitar_codigo.php
```

**Si ves JSON → ¡Funciona!**
**Si NO ves nada → Reemplaza los archivos**

---

## 📞 ARCHIVOS DE AYUDA CREADOS

- **PROBAR_SERVIDOR.bat** ← Ejecuta este ahora
- **COMO_SUBIR_CON_WINSCP.md** ← Ya lo hiciste ✅
- **GUIA_VISUAL_WINSCP.md** ← Ya lo hiciste ✅
- **INDICE_SOLUCION.md** ← Índice completo

---

¡Ya casi terminas! Solo falta probar. 💪


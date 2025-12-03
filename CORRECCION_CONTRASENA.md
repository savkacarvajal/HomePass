# 🔧 CORRECCIÓN APLICADA - Reemplazar Archivo

## ✅ PROBLEMA IDENTIFICADO Y CORREGIDO

**Error:** `Unknown column 'password' in 'field list'`

**Causa:** La columna en la BD se llama **`contrasena`** (no `password`)

**Solución:** ✅ Ya corregí el archivo `apimodificarclave.php`

---

## 📋 CAMBIO REALIZADO

**ANTES (incorrecto):**
```php
UPDATE users SET password = ? WHERE email = ?
```

**AHORA (correcto):**
```php
UPDATE users SET contrasena = ? WHERE email = ?
```

---

## 🚀 ACCIÓN INMEDIATA: Reemplazar el Archivo

### PASO 1: Abrir WinSCP

Ya sabes cómo hacerlo:
- Conecta a `98.95.39.30` como `ec2-user` con tu `.ppk`

### PASO 2: Reemplazar el archivo

- **Panel IZQUIERDO:** `C:\Users\savka\AndroidStudioProjects\Test\`
  - Busca: `apimodificarclave.php`
  
- **Panel DERECHO:** `/var/www/html/`
  - Verás: `apimodificarclave.php` (el viejo)

- **ARRASTRA** el archivo del panel izquierdo sobre el del derecho
- **Confirma "Overwrite/Sobrescribir"**

### PASO 3: Probar de nuevo

Desde **cmd.exe**:
```batch
cd C:\Users\savka\AndroidStudioProjects\Test
PROBAR_CREAR_CONTRASENA.bat
```

**AHORA deberías ver:**
```json
{"status":"success","message":"Contraseña actualizada correctamente"}
```

---

## 🎯 ESTRUCTURA DE LA TABLA `users`

Según tu captura de phpMyAdmin:

| Columna | Tipo | Uso |
|---------|------|-----|
| `id` | int | ID único |
| `nombres` | varchar | Nombre del usuario |
| `apellidos` | varchar | Apellidos |
| `email` | varchar | Email (único) |
| **`contrasena`** | varchar | **Contraseña hasheada** ← Esta columna |

---

## ✅ CHECKLIST

```
[✓] Archivo apimodificarclave.php corregido
[ ] WinSCP abierto y conectado
[ ] Archivo reemplazado en /var/www/html/
[ ] PROBAR_CREAR_CONTRASENA.bat ejecutado
[ ] Ver JSON con "success"
[ ] Probar en app Android
[ ] ¡FUNCIONA! 🎉
```

---

## 📱 PRUEBA COMPLETA EN LA APP

Una vez que el comando devuelva "success":

1. Abre la app
2. Recuperar Contraseña → `luna@gmail.com`
3. Solicitar Código → Ver código (ej: 51861)
4. Validar Código → Ingresar código
5. **Crear Nueva Contraseña:**
   - Nueva: `Test1234!`
   - Confirmar: `Test1234!`
6. **¡SweetAlert de éxito!** ✓
7. **Login** con:
   - Email: `luna@gmail.com`
   - Contraseña: `Test1234!`
8. **¡Ingreso exitoso!** 🎉

---

## 🚀 ACCIÓN AHORA

**Reemplaza el archivo `apimodificarclave.php` en WinSCP.**

Es exactamente el mismo proceso que hiciste antes:
1. Abre WinSCP
2. Arrastra el archivo sobre el existente
3. Confirma "Overwrite"
4. Ejecuta `PROBAR_CREAR_CONTRASENA.bat`

**Tiempo:** 1 minuto

---

¡Ya está corregido! Solo falta reemplazarlo en el servidor. 💪


# 🚨 PROBLEMA DETECTADO Y SOLUCIÓN

## ❌ LO QUE PASÓ

Ejecutaste `PROBAR_SERVIDOR.bat` y viste esto:

```
1. Probando test_conexion.php...
(vacío - sin respuesta)

2. Probando solicitar_codigo.php...
(vacío - sin respuesta)

3. Probando validar_codigo.php...
(vacío - sin respuesta)
```

**DIAGNÓSTICO:** Los archivos PHP en el servidor **no están devolviendo nada**.

---

## 🔍 CAUSA

Los archivos que subiste con WinSCP son probablemente los **VIEJOS/INCOMPLETOS**, no los archivos **NUEVOS** que creé con toda la lógica.

**Comparación:**

❌ **Archivos VIEJOS en servidor** (incompletos):
- `solicitar_codigo.php` (3 KB) - tiene comentarios "Omitido por concisión"
- `validar_codigo.php` (5 KB) - puede estar incompleto

✅ **Archivos NUEVOS que creé** (completos):
- `solicitar_codigo_NUEVO.php` (4 KB) - con lógica completa de INSERT
- `validar_codigo_NUEVO.php` (4 KB) - con validación completa

---

## ✅ SOLUCIÓN: REEMPLAZAR ARCHIVOS EN WINSCP

### PASO 1: Verificar que tienes los archivos NUEVOS en tu PC

Ejecuta desde cmd.exe:
```batch
cd C:\Users\savka\AndroidStudioProjects\Test
VERIFICAR_ARCHIVOS.bat
```

Deberías ver:
```
[OK] solicitar_codigo_NUEVO.php existe (4,141 bytes)
[OK] validar_codigo_NUEVO.php existe (4,119 bytes)
```

---

### PASO 2: Reemplazar con WinSCP

#### A. Abre WinSCP y conecta a tu servidor
- Host: `98.95.39.30`
- Usuario: `ec2-user`
- Clave: Tu archivo `.ppk`

#### B. Localiza los archivos

**Panel IZQUIERDO (tu PC):**
```
C:\Users\savka\AndroidStudioProjects\Test\

Busca estos archivos:
✅ solicitar_codigo_NUEVO.php (4 KB)
✅ validar_codigo_NUEVO.php (4 KB)
```

**Panel DERECHO (servidor):**
```
/var/www/html/

Verás estos archivos:
📄 solicitar_codigo.php (3 KB) ← Reemplazar este
📄 validar_codigo.php (5 KB) ← Reemplazar este
```

#### C. ARRASTRAR Y REEMPLAZAR

**1. Solicitar código:**
- **Selecciona** `solicitar_codigo_NUEVO.php` en panel izquierdo
- **Arrástralo** sobre `solicitar_codigo.php` en panel derecho
- WinSCP preguntará: **"Target file already exists. Overwrite?"**
- **Selecciona:** ✅ "Overwrite" o "Yes"
- **Confirma**

**2. Validar código:**
- **Selecciona** `validar_codigo_NUEVO.php` en panel izquierdo
- **Arrástralo** sobre `validar_codigo.php` en panel derecho
- WinSCP preguntará: **"Target file already exists. Overwrite?"**
- **Selecciona:** ✅ "Overwrite" o "Yes"
- **Confirma**

#### D. Verificar en WinSCP

Después de reemplazar, verifica en el panel derecho:
```
📄 solicitar_codigo.php - Tamaño: ~4 KB - Modificado: HOY (fecha actual)
📄 validar_codigo.php - Tamaño: ~4 KB - Modificado: HOY (fecha actual)
```

---

### PASO 3: PROBAR DE NUEVO

Desde cmd.exe:
```batch
cd C:\Users\savka\AndroidStudioProjects\Test
PROBAR_SERVIDOR.bat
```

**Ahora DEBERÍAS ver:**
```json
1. Probando test_conexion.php...
{"status":"success","message":"¡Servidor y BD funcionando!","timestamp":"..."}

2. Probando solicitar_codigo.php...
{"status":"success","message":"Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 12345)"}

3. Probando validar_codigo.php...
{"status":"error","message":"Código incorrecto"}
```

✅ **Si ves JSON → ¡FUNCIONA!**

---

## 📸 GUÍA VISUAL DE WINSCP

### Vista del reemplazo:

```
┌──────────────────────────────────────────────────────────────┐
│  WinSCP - Overwrite Confirmation                             │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  Target file already exists:                                 │
│  /var/www/html/solicitar_codigo.php (3,456 bytes)           │
│                                                              │
│  Source file:                                                │
│  C:\Users\...\solicitar_codigo_NUEVO.php (4,141 bytes)      │
│                                                              │
│  What do you want to do?                                     │
│                                                              │
│  (•) Overwrite                                               │
│  ( ) Skip                                                    │
│  ( ) Append                                                  │
│  ( ) Resume                                                  │
│                                                              │
│  ☑ Newer only                                                │
│                                                              │
│  [  OK  ]  [ Skip ]  [ Cancel ]                              │
└──────────────────────────────────────────────────────────────┘
```

**Acción:** Click en **[OK]** con **"Overwrite"** seleccionado.

---

## 🎯 CHECKLIST DE ACCIÓN

```
[ ] Ejecutar VERIFICAR_ARCHIVOS.bat
[ ] Confirmar que archivos *_NUEVO.php existen (4 KB cada uno)
[ ] Abrir WinSCP
[ ] Conectar a 98.95.39.30
[ ] Panel izquierdo en C:\Users\savka\AndroidStudioProjects\Test\
[ ] Panel derecho en /var/www/html/
[ ] Arrastrar solicitar_codigo_NUEVO.php sobre solicitar_codigo.php
[ ] Confirmar OVERWRITE
[ ] Arrastrar validar_codigo_NUEVO.php sobre validar_codigo.php
[ ] Confirmar OVERWRITE
[ ] Ejecutar PROBAR_SERVIDOR.bat de nuevo
[ ] Ver JSON con status y message ✅
[ ] Probar en app Android
```

---

## ⚡ COMPARACIÓN DE ARCHIVOS

### Cómo saber si el archivo es el correcto:

**En WinSCP, haz click derecho en `solicitar_codigo.php` → "Edit"**

#### ❌ SI VES ESTO (archivo viejo):
```php
<?php
// ...
if ($email_exists) {
    $code = str_pad(random_int(10000, 99999), 5, '0', STR_PAD_LEFT);
    // ... (Lógica de DELETE y INSERT con sentencias preparadas) ...
    
    // Asumiendo éxito de la inserción:
    $response['status'] = 'success';
}
```
**Problema:** Dice "Asumiendo éxito" pero NO hay INSERT real.

#### ✅ SI VES ESTO (archivo nuevo):
```php
<?php
// solicitar_codigo.php - VERSIÓN COMPLETA Y FUNCIONAL

// Limpiar TODA la salida previa y buffer
while (ob_get_level()) {
    ob_end_clean();
}
ob_start();

// Headers
header('Content-Type: application/json; charset=utf-8');
// ...

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
    // ... código completo de INSERT
}
```
**Correcto:** Tiene la lógica COMPLETA de DELETE + INSERT.

---

## 🆘 SI NO ENCUENTRAS LOS ARCHIVOS *_NUEVO.php

Si `VERIFICAR_ARCHIVOS.bat` dice que NO existen, significa que algo falló al crearlos.

**Solución:** Los archivos YA ESTÁN creados en tu proyecto. Verifica en el explorador de archivos:

1. Abre el Explorador de Windows
2. Ve a: `C:\Users\savka\AndroidStudioProjects\Test\`
3. Busca archivos que terminen en `_NUEVO.php`
4. Deberías ver:
   - `solicitar_codigo_NUEVO.php` (4 KB)
   - `validar_codigo_NUEVO.php` (4 KB)

Si NO están, déjame saberlo y los recreo.

---

## 🚀 RESUMEN

**El problema:** Archivos en servidor están vacíos/incompletos
**La solución:** Reemplazarlos con archivos `*_NUEVO.php` usando WinSCP
**Tiempo:** 2 minutos

**EJECUTA AHORA:**
```batch
VERIFICAR_ARCHIVOS.bat
```

Luego sigue los pasos de reemplazo en WinSCP.

¡Ya casi está! 💪


# 🖼️ GUÍA VISUAL WINSCP - CAPTURAS PASO A PASO

## 📍 PANTALLA 1: Configuración de WinSCP

```
┌─────────────────────────────────────────────────────────────┐
│                    WinSCP Login                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  File protocol:  [SFTP v]                                   │
│                                                             │
│  Host name:      [98.95.39.30                ]              │
│                                                             │
│  Port number:    [22                          ]              │
│                                                             │
│  User name:      [ec2-user                    ]              │
│                                                             │
│  Password:       [                            ]              │
│                  (dejar vacío - usa clave)                  │
│                                                             │
│  [Advanced...]  [Save...]  [Login]                          │
└─────────────────────────────────────────────────────────────┘
```

**Acción:**
1. Click en **[Advanced...]**
2. Ve a **SSH → Authentication**
3. En **Private key file**, busca tu archivo `.pem` o `.ppk`
4. Click **OK**
5. Click **[Login]**

---

## 📍 PANTALLA 2: Vista de archivos conectada

```
┌──────────────────────────────────────────────────────────────────────────────┐
│  WinSCP - ec2-user@98.95.39.30                                     [_][□][X] │
├──────────────────────────────────────────────────────────────────────────────┤
│ File  Commands  Options  Help                                                │
├───────────────────────────────┬───────────────────────────────────────────────┤
│  LOCAL (tu PC)                │  REMOTE (servidor AWS)                       │
│  C:\Users\savka\...Test\      │  /home/ec2-user/                             │
├───────────────────────────────┼───────────────────────────────────────────────┤
│                               │                                              │
│  📁 app/                      │  📁 .ssh/                                    │
│  📁 gradle/                   │  📄 .bash_history                            │
│  📄 build.gradle.kts          │  📄 .bash_logout                             │
│  📄 conexion.php              │  📄 .bash_profile                            │
│  📄 crear_tabla_codigos.sql   │  📄 .bashrc                                  │
│  📄 solicitar_codigo_NUEVO.php ← ESTE ARCHIVO                                │
│  📄 validar_codigo_NUEVO.php  ← ESTE ARCHIVO                                │
│  📄 test_conexion.php         │                                              │
│  📄 test_recuperar.bat        │  ⬅️ ARRASTRA LOS ARCHIVOS AQUÍ              │
│                               │                                              │
│                               │                                              │
│                               │                                              │
└───────────────────────────────┴───────────────────────────────────────────────┘
```

**Acción:**
1. En el **panel IZQUIERDO**, navega hasta que veas los archivos `*_NUEVO.php`
2. En el **panel DERECHO**, asegúrate de estar en `/home/ec2-user/`
3. **Selecciona** `solicitar_codigo_NUEVO.php` con el mouse
4. **Arrástralo** al panel derecho
5. **Repite** con `validar_codigo_NUEVO.php`

---

## 📍 PANTALLA 3: Confirmación de transferencia

```
┌─────────────────────────────────────────────────────────┐
│              Upload                                     │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  Upload file 'solicitar_codigo_NUEVO.php'               │
│  to '/home/ec2-user/solicitar_codigo_NUEVO.php'?        │
│                                                         │
│  ▣ Preserve timestamp                                   │
│  ▣ Calculate total size                                 │
│                                                         │
│  [  Copy  ]  [ Copy All ]  [ Skip ]  [ Cancel ]         │
└─────────────────────────────────────────────────────────┘
```

**Acción:**
- Click en **[Copy]** o **[Copy All]**

---

## 📍 PANTALLA 4: Después de subir

```
┌──────────────────────────────────────────────────────────────────────────────┐
│  WinSCP - ec2-user@98.95.39.30                                     [_][□][X] │
├──────────────────────────────────────────────────────────────────────────────┤
│  LOCAL (tu PC)                │  REMOTE (servidor AWS)                       │
│  C:\Users\savka\...Test\      │  /home/ec2-user/                             │
├───────────────────────────────┼───────────────────────────────────────────────┤
│                               │                                              │
│  📄 solicitar_codigo_NUEVO.php│  📁 .ssh/                                    │
│  📄 validar_codigo_NUEVO.php  │  📄 .bash_history                            │
│                               │  📄 solicitar_codigo_NUEVO.php ✅ SUBIDO     │
│                               │  📄 validar_codigo_NUEVO.php   ✅ SUBIDO     │
│                               │                                              │
│                               │  ✅ 2 archivos transferidos                  │
│                               │                                              │
└───────────────────────────────┴───────────────────────────────────────────────┘
```

**Verificación:**
- En el **panel DERECHO** deberías ver los 2 archivos PHP
- Tamaño aproximado: 4 KB cada uno
- Si no ves los archivos, presiona **F5** para refrescar

---

## 💻 SIGUIENTE PASO: SSH/PuTTY

Ahora necesitas conectar por SSH para mover los archivos a `/var/www/html/`

### Si usas PuTTY:

```
┌─────────────────────────────────────────────────────┐
│  PuTTY Configuration                                │
├─────────────────────────────────────────────────────┤
│                                                     │
│  Host Name: ec2-user@98.95.39.30                    │
│  Port: 22                                           │
│  Connection type: (•) SSH                           │
│                                                     │
│  Sidebar:                                           │
│    └─ SSH                                           │
│       └─ Auth                                       │
│          └─ Credentials                             │
│             Private key file: (tu archivo .ppk)     │
│                                                     │
│  [Open]                                             │
└─────────────────────────────────────────────────────┘
```

### Comandos a ejecutar en la terminal SSH:

```bash
[ec2-user@ip-172-31-23-229 ~]$ sudo mv /home/ec2-user/solicitar_codigo_NUEVO.php /var/www/html/solicitar_codigo.php
[ec2-user@ip-172-31-23-229 ~]$ sudo mv /home/ec2-user/validar_codigo_NUEVO.php /var/www/html/validar_codigo.php
[ec2-user@ip-172-31-23-229 ~]$ sudo chmod 644 /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php
[ec2-user@ip-172-31-23-229 ~]$ sudo chown apache:apache /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php
[ec2-user@ip-172-31-23-229 ~]$ ls -lh /var/www/html/ | grep codigo

-rw-r--r-- 1 apache apache 4.1K Nov  7 13:21 solicitar_codigo.php
-rw-r--r-- 1 apache apache 4.0K Nov  7 13:21 validar_codigo.php
```

**✅ Si ves esto, ¡está listo!**

---

## 🧪 PRUEBA FINAL desde Windows

Abre **cmd.exe** (Win + R → cmd):

```
C:\Users\savka\AndroidStudioProjects\Test>curl -v -X POST -d "email=test@example.com" http://98.95.39.30/solicitar_codigo.php

< HTTP/1.1 200 OK
< Content-Type: application/json; charset=utf-8
< Content-Length: 124
<
{"status":"success","message":"Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 45678)"}
```

**✅ Si ves el JSON con status y mensaje → ¡FUNCIONA!**
**❌ Si ves Content-Length: 0 → Revisa los pasos anteriores**

---

## 📱 PRUEBA EN LA APP ANDROID

1. **Abre la app**
2. **Click en "¿Olvidaste tu contraseña?"**
3. **Ingresa un email** (ej: luna@gmail.com)
4. **Click en "Solicitar Código"**

**Deberías ver esto:**

```
┌────────────────────────────────────┐
│        ¡Correo enviado! ✓          │
│                                    │
│  Si el email está registrado, se   │
│  ha enviado un código de           │
│  restablecimiento. (DEBUG: 45678)  │
│                                    │
│            [  OK  ]                 │
└────────────────────────────────────┘
```

5. **Aparecen los campos para ingresar código:**

```
┌────────────────────────────────────┐
│  Recuperar Contraseña              │
│                                    │
│  Email: luna@gmail.com             │
│                                    │
│  Código: [_____]  ← INGRESA 45678  │
│                                    │
│  [ Validar Código ]                 │
└────────────────────────────────────┘
```

6. **Ingresa el código** que viste en el mensaje
7. **Click en "Validar Código"**
8. **Te lleva a crear nueva contraseña** ✅

---

## ✅ CHECKLIST VISUAL

```
WinSCP
  ├─ [✓] Conexión establecida a 98.95.39.30
  ├─ [✓] Panel izquierdo en C:\Users\savka\...\Test\
  ├─ [✓] Panel derecho en /home/ec2-user/
  ├─ [✓] solicitar_codigo_NUEVO.php arrastrado
  ├─ [✓] validar_codigo_NUEVO.php arrastrado
  └─ [✓] Archivos visibles en panel derecho

SSH/PuTTY
  ├─ [✓] Conectado a 98.95.39.30
  ├─ [✓] sudo mv comandos ejecutados
  ├─ [✓] chmod 644 ejecutado
  ├─ [✓] chown apache:apache ejecutado
  └─ [✓] ls -lh muestra archivos correctos

Pruebas
  ├─ [✓] curl desde servidor → JSON OK
  ├─ [✓] curl desde PC → JSON OK
  └─ [✓] App Android → Funciona ✅
```

---

## 🎯 TIEMPO ESTIMADO

- **WinSCP:** 2 minutos
- **SSH comandos:** 1 minuto
- **Pruebas:** 1 minuto
- **Total:** ~4 minutos ⚡

---

## 🆘 PROBLEMAS COMUNES

### ❌ No puedo conectar WinSCP
**Solución:** Verifica que tu clave `.pem` esté configurada en Advanced → SSH → Authentication

### ❌ Permission denied al mover archivos
**Solución:** Usa `sudo` antes de cada comando `mv`

### ❌ Curl devuelve Content-Length: 0
**Solución:** Verifica que los archivos se movieron correctamente:
```bash
cat /var/www/html/solicitar_codigo.php | head -10
```

Deberías ver:
```php
<?php
// solicitar_codigo.php - VERSIÓN COMPLETA Y FUNCIONAL
```

---

## 🎉 CUANDO TODO FUNCIONE

**En la app verás:**
- ✅ SweetAlert verde con código
- ✅ Campos de validación aparecen
- ✅ Puedes cambiar la contraseña
- ✅ Login exitoso con nueva contraseña

**¡LISTO!** 🚀


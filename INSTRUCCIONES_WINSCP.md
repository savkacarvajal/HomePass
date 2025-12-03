# 📤 GUÍA: Subir archivos PHP con WinSCP

## 🎯 ARCHIVOS QUE DEBES SUBIR

Desde tu carpeta local:
```
C:\Users\savka\AndroidStudioProjects\Test\
```

Debes subir estos archivos al servidor:

| Archivo Local | Archivo en Servidor |
|---------------|---------------------|
| `solicitar_codigo_NUEVO.php` | `/var/www/html/solicitar_codigo.php` |
| `validar_codigo_NUEVO.php` | `/var/www/html/validar_codigo.php` |

---

## 📋 PASOS DETALLADOS CON WINSCP

### 1️⃣ Configurar la Conexión

1. **Abre WinSCP**

2. **Crea una nueva conexión:**
   ```
   File Protocol: SFTP
   Host name: 98.95.39.30
   Port number: 22
   User name: ec2-user
   ```

3. **Configurar la clave privada:**
   - Click en **"Advanced..."**
   - Ve a **"SSH" → "Authentication"**
   - En **"Private key file"**, busca tu archivo `.pem` o `.ppk`
   - Si tienes un archivo `.pem`, WinSCP te preguntará si quieres convertirlo a `.ppk`
   - Click en **"OK"**

4. **Guardar la sesión:**
   - Click en **"Save"**
   - Nombre: "Servidor AWS - IoT"
   - Click en **"OK"**

5. **Conectar:**
   - Click en **"Login"**

---

### 2️⃣ Subir los Archivos

Una vez conectado, verás dos paneles:
```
┌─────────────────────────────────────────────────────────────┐
│  Panel Izquierdo (LOCAL)    │   Panel Derecho (SERVIDOR)   │
│  C:\Users\savka\...          │   /home/ec2-user/            │
└─────────────────────────────────────────────────────────────┘
```

#### **Paso A: Navegar en el panel IZQUIERDO (local)**
```
C:\Users\savka\AndroidStudioProjects\Test\
```
Busca los archivos:
- ✅ `solicitar_codigo_NUEVO.php`
- ✅ `validar_codigo_NUEVO.php`

#### **Paso B: Navegar en el panel DERECHO (servidor)**
```
/var/www/html/
```

**¿No tienes permisos?** Necesitas privilegios de root. Haz esto:

1. **Opción A - Subir temporalmente y mover:**
   - Sube los archivos a `/home/ec2-user/` (tu carpeta personal)
   - Abre **PuTTY** o terminal SSH
   - Ejecuta estos comandos:
   ```bash
   ssh -i tu-clave.pem ec2-user@98.95.39.30
   
   # Mover y renombrar archivos
   sudo mv /home/ec2-user/solicitar_codigo_NUEVO.php /var/www/html/solicitar_codigo.php
   sudo mv /home/ec2-user/validar_codigo_NUEVO.php /var/www/html/validar_codigo.php
   
   # Establecer permisos correctos
   sudo chmod 644 /var/www/html/solicitar_codigo.php
   sudo chmod 644 /var/www/html/validar_codigo.php
   
   # Establecer propietario correcto
   sudo chown apache:apache /var/www/html/solicitar_codigo.php
   sudo chown apache:apache /var/www/html/validar_codigo.php
   ```

2. **Opción B - Usar WinSCP con sudo (avanzado):**
   - Ve a **"Options" → "Preferences"**
   - **"Transfer" → "Edit"**
   - En **"Shell"**, cambia a: `sudo su -`
   - Esto te dará permisos de root en WinSCP

#### **Paso C: Arrastrar y soltar**

1. **Selecciona** `solicitar_codigo_NUEVO.php` en el panel izquierdo
2. **Arrástralo** al panel derecho (`/var/www/html/` o `/home/ec2-user/`)
3. WinSCP preguntará: **"Confirm"**
   - Si subes a `/home/ec2-user/`, click **"OK"**
   - Si subes directamente a `/var/www/html/`, puede pedir confirmación
4. **Renombrar al soltar:**
   - WinSCP puede preguntar por el nombre
   - Cambia `solicitar_codigo_NUEVO.php` → `solicitar_codigo.php`

5. **Repite** con `validar_codigo_NUEVO.php` → `validar_codigo.php`

---

### 3️⃣ Verificar los Archivos en el Servidor

**Opción A: Desde WinSCP**
- Click derecho en el archivo → **"Properties"**
- Verifica que los permisos sean: **644** o **rw-r--r--**

**Opción B: Desde terminal SSH**
```bash
ssh -i tu-clave.pem ec2-user@98.95.39.30

# Ver archivos
ls -la /var/www/html/ | grep -E "(solicitar|validar)_codigo"

# Deberías ver algo como:
# -rw-r--r-- 1 apache apache 3245 Nov  7 16:30 solicitar_codigo.php
# -rw-r--r-- 1 apache apache 2847 Nov  7 16:30 validar_codigo.php

# Ver las primeras líneas del archivo
head -20 /var/www/html/solicitar_codigo.php
```

Deberías ver:
```php
<?php
// solicitar_codigo.php - VERSIÓN COMPLETA Y FUNCIONAL

// Limpiar TODA la salida previa y buffer
while (ob_get_level()) {
    ob_end_clean();
}
```

---

## ✅ CHECKLIST DE VERIFICACIÓN

```
📤 Subida de Archivos
  ├─ [ ] solicitar_codigo_NUEVO.php subido
  ├─ [ ] validar_codigo_NUEVO.php subido
  ├─ [ ] Archivos renombrados (sin _NUEVO)
  ├─ [ ] Ubicados en /var/www/html/
  ├─ [ ] Permisos 644 establecidos
  └─ [ ] Propietario apache:apache

🔍 Verificación
  ├─ [ ] Archivo visible en WinSCP
  ├─ [ ] Tamaño > 0 bytes
  └─ [ ] Contenido correcto (head -20)
```

---

## 🧪 PROBAR QUE FUNCIONE

### Desde Windows (cmd.exe):

1. **Abrir terminal:**
   - Presiona `Win + R`
   - Escribe `cmd` y presiona Enter

2. **Ejecutar pruebas:**
   ```batch
   cd C:\Users\savka\AndroidStudioProjects\Test
   test_recuperar.bat
   ```

3. **O prueba manual:**
   ```batch
   curl -v -X POST -d "email=test@example.com" http://98.95.39.30/solicitar_codigo.php
   ```

### ✅ Respuesta esperada:
```
< HTTP/1.1 200 OK
< Content-Type: application/json; charset=utf-8
< Content-Length: 87
< 
{"status":"success","message":"Si el email está registrado... (DEBUG: 12345)"}
```

### ❌ Si ves esto, NO funcionó:
```
< Content-Length: 0
<
(vacío)
```

---

## 🆘 SOLUCIÓN DE PROBLEMAS

### ❌ Error: "Permission denied" al subir

**Solución:**
1. Sube los archivos a `/home/ec2-user/`
2. Usa SSH para moverlos con `sudo mv`

### ❌ WinSCP pide convertir .pem a .ppk

**Solución:**
1. Click en **"OK"** para convertir
2. Guarda el archivo `.ppk` en la misma carpeta
3. Usa el archivo `.ppk` en futuras conexiones

### ❌ Error: "Connection refused"

**Solución:**
1. Verifica que el servidor esté encendido
2. Verifica que el puerto 22 (SSH) esté abierto en el Security Group de AWS
3. Prueba con PuTTY primero para verificar la conexión

### ❌ Error: "Host key verification failed"

**Solución:**
1. Click en **"Yes"** para aceptar la huella del servidor
2. O borra el archivo `known_hosts` en `C:\Users\savka\.ssh\`

---

## 📸 CAPTURAS DE PANTALLA DE REFERENCIA

### WinSCP - Pantalla de Login:
```
┌─────────────────────────────────────────┐
│  File protocol: SFTP                    │
│  Host name: 98.95.39.30                 │
│  Port: 22                               │
│  User name: ec2-user                    │
│  Password: (vacío - usa clave privada)  │
│                                         │
│  [Advanced...] [Save...] [Login]        │
└─────────────────────────────────────────┘
```

### WinSCP - Vista de archivos:
```
┌──────────────────────────────────────────────────────────────┐
│  Local: C:\Users\savka\...  │  Remote: /var/www/html/       │
├─────────────────────────────┼───────────────────────────────┤
│  📄 solicitar_codigo_NUEVO  │  📄 conexion.php              │
│  📄 validar_codigo_NUEVO    │  📄 get_users.php             │
│  📄 test_conexion.php       │  📄 login.php                 │
│                             │  📄 register.php              │
│                             │  ⬅ ARRASTRA AQUÍ             │
└─────────────────────────────┴───────────────────────────────┘
```

---

## 🎯 RESUMEN RÁPIDO

1. **Conectar WinSCP** a `ec2-user@98.95.39.30` con tu clave `.pem`
2. **Subir archivos** `*_NUEVO.php` a `/home/ec2-user/`
3. **SSH al servidor** y ejecutar:
   ```bash
   sudo mv /home/ec2-user/solicitar_codigo_NUEVO.php /var/www/html/solicitar_codigo.php
   sudo mv /home/ec2-user/validar_codigo_NUEVO.php /var/www/html/validar_codigo.php
   sudo chmod 644 /var/www/html/*.php
   sudo chown apache:apache /var/www/html/*.php
   ```
4. **Probar** con `test_recuperar.bat`
5. **Abrir la app** y probar recuperar contraseña

---

## ✅ CUANDO TODO FUNCIONE

Ejecuta `curl` y deberías ver:
```json
{
  "status": "success",
  "message": "Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 12345)"
}
```

¡Eso significa que ya puedes probar en la app! 🎉


# 🚨 SOLUCIÓN RÁPIDA - Subir Archivos con WinSCP

## ⚠️ TU PROBLEMA ACTUAL

Intentaste ejecutar esto en el servidor:
```bash
sudo mv /home/ec2-user/solicitar_codigo_NUEVO.php /var/www/html/solicitar_codigo.php
```

**ERROR:** `No such file or directory`

**CAUSA:** Los archivos todavía están en tu PC, NO en el servidor.

---

## ✅ SOLUCIÓN EN 3 PASOS

### PASO 1: Abrir WinSCP en tu PC Windows

1. Busca **WinSCP** en tu PC y ábrelo
2. Si no aparece una ventana de conexión, haz click en **"New Session"**

---

### PASO 2: Conectar al Servidor

Ingresa estos datos:

```
File protocol: SFTP
Host name: 98.95.39.30
Port: 22
User name: ec2-user
```

**Clave privada:**
- Click en **"Advanced..."**
- Ve a **SSH → Authentication**
- En **"Private key file"**, busca tu archivo `.pem` o `.ppk`
- Click **OK**
- Click **Login**

**Si pide convertir .pem a .ppk, acepta.**

---

### PASO 3: Subir los 2 Archivos

Una vez conectado verás **2 PANELES:**

#### Panel IZQUIERDO (tu PC):
Navega a:
```
C:\Users\savka\AndroidStudioProjects\Test\
```

Deberías ver estos archivos:
- ✅ `solicitar_codigo_NUEVO.php` (4 KB)
- ✅ `validar_codigo_NUEVO.php` (4 KB)

#### Panel DERECHO (servidor):
Navega a:
```
/home/ec2-user/
```

#### ACCIÓN - Arrastrar y soltar:
1. **Selecciona** `solicitar_codigo_NUEVO.php` en el panel izquierdo
2. **Arrástralo** al panel derecho (a la carpeta `/home/ec2-user/`)
3. **Confirma** cuando pregunte
4. **Repite** con `validar_codigo_NUEVO.php`

#### VERIFICAR:
En el panel derecho (servidor) deberías ver:
```
/home/ec2-user/
  solicitar_codigo_NUEVO.php  ✅
  validar_codigo_NUEVO.php    ✅
```

---

## 📋 DESPUÉS DE SUBIR LOS ARCHIVOS

**Ahora SÍ** ejecuta estos comandos en SSH:

```bash
# Mover archivos
sudo mv /home/ec2-user/solicitar_codigo_NUEVO.php /var/www/html/solicitar_codigo.php
sudo mv /home/ec2-user/validar_codigo_NUEVO.php /var/www/html/validar_codigo.php

# Establecer permisos
sudo chmod 644 /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php

# Establecer propietario
sudo chown apache:apache /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php

# Verificar
ls -lh /var/www/html/ | grep codigo
```

**Deberías ver:**
```
-rw-r--r-- 1 apache apache 4.1K Nov  7 solicitar_codigo.php
-rw-r--r-- 1 apache apache 4.0K Nov  7 validar_codigo.php
```

---

## 🧪 PROBAR QUE FUNCIONE

Desde el servidor SSH:
```bash
curl -X POST -d "email=test@example.com" http://localhost/solicitar_codigo.php
```

**Respuesta esperada:**
```json
{"status":"success","message":"Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 12345)"}
```

---

## 🆘 SI NO TIENES WINSCP INSTALADO

### Opción A: Descargar WinSCP (recomendado)
1. Ve a: https://winscp.net/
2. Descarga e instala
3. Sigue los pasos anteriores

### Opción B: Usar SCP desde PowerShell (avanzado)
```powershell
scp -i "C:\ruta\a\tu\clave.pem" "C:\Users\savka\AndroidStudioProjects\Test\solicitar_codigo_NUEVO.php" ec2-user@98.95.39.30:/home/ec2-user/

scp -i "C:\ruta\a\tu\clave.pem" "C:\Users\savka\AndroidStudioProjects\Test\validar_codigo_NUEVO.php" ec2-user@98.95.39.30:/home/ec2-user/
```

---

## 📝 RESUMEN

```
❌ ANTES:
   Archivos en PC → Intentaste moverlos en servidor → ERROR

✅ AHORA:
   1. Abrir WinSCP
   2. Conectar a 98.95.39.30
   3. Arrastrar archivos del panel izquierdo al derecho
   4. Ejecutar comandos mv en SSH
   5. ¡Listo!
```

---

## ✅ CHECKLIST

```
[ ] WinSCP instalado y abierto
[ ] Conectado a 98.95.39.30 como ec2-user
[ ] Panel izquierdo en C:\Users\savka\AndroidStudioProjects\Test\
[ ] Panel derecho en /home/ec2-user/
[ ] solicitar_codigo_NUEVO.php arrastrado al servidor ✅
[ ] validar_codigo_NUEVO.php arrastrado al servidor ✅
[ ] Comandos mv ejecutados en SSH
[ ] Permisos establecidos
[ ] Prueba con curl → JSON OK
```

---

## 🎯 TIEMPO ESTIMADO

- Instalar WinSCP (si no lo tienes): 2 minutos
- Configurar conexión: 1 minuto
- Subir archivos: 30 segundos
- Ejecutar comandos SSH: 1 minuto

**TOTAL: ~5 minutos**

---

## 💡 IMPORTANTE

Los archivos están en tu PC en:
```
C:\Users\savka\AndroidStudioProjects\Test\
  ├─ solicitar_codigo_NUEVO.php ✅ (4,141 bytes)
  └─ validar_codigo_NUEVO.php   ✅ (4,119 bytes)
```

**Solo necesitas transferirlos al servidor con WinSCP.**

¡Es muy fácil! Solo arrastra y suelta. 🚀


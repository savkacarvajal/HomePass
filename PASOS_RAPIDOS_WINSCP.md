# 🚀 PASOS RÁPIDOS - SOLUCIÓN CON WINSCP

## ⚡ RESUMEN DE 3 MINUTOS

Tu problema: **App recibe respuesta vacía** (Content-Length: 0)
Causa: **Archivos PHP incompletos en el servidor**
Solución: **Subir archivos PHP COMPLETOS**

---

## 📋 PASOS EXACTOS

### 1️⃣ ABRIR WINSCP Y CONECTAR

1. Abre **WinSCP**
2. Configura la conexión:
   - **Host:** `98.95.39.30`
   - **Puerto:** `22`
   - **Usuario:** `ec2-user`
   - **Clave privada:** Tu archivo `.pem` o `.ppk`
3. Click en **Login**

---

### 2️⃣ SUBIR LOS 2 ARCHIVOS PHP

En **WinSCP** verás 2 paneles:

**Panel IZQUIERDO (tu PC):**
- Navega a: `C:\Users\savka\AndroidStudioProjects\Test\`
- Busca estos 2 archivos:
  - ✅ `solicitar_codigo_NUEVO.php`
  - ✅ `validar_codigo_NUEVO.php`

**Panel DERECHO (servidor):**
- Navega a: `/home/ec2-user/`

**Acción:**
- **ARRASTRA** `solicitar_codigo_NUEVO.php` del panel izquierdo al derecho
- **ARRASTRA** `validar_codigo_NUEVO.php` del panel izquierdo al derecho
- Confirma cuando pregunte

---

### 3️⃣ CONECTAR POR SSH Y EJECUTAR COMANDOS

Abre **PuTTY** o tu cliente SSH y ejecuta:

```bash
ssh -i TU_CLAVE.pem ec2-user@98.95.39.30
```

**Copia y pega estos comandos UNO POR UNO:**

```bash
sudo mv /home/ec2-user/solicitar_codigo_NUEVO.php /var/www/html/solicitar_codigo.php
```

```bash
sudo mv /home/ec2-user/validar_codigo_NUEVO.php /var/www/html/validar_codigo.php
```

```bash
sudo chmod 644 /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php
```

```bash
sudo chown apache:apache /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php
```

---

### 4️⃣ VERIFICAR QUE FUNCIONÓ

**En el servidor (SSH), ejecuta:**

```bash
curl -X POST -d "email=test@example.com" http://localhost/solicitar_codigo.php
```

**Respuesta esperada:**
```json
{"status":"success","message":"Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 12345)"}
```

✅ **Si ves JSON → ¡FUNCIONÓ!**
❌ **Si ves vacío → Revisa los pasos**

---

### 5️⃣ CREAR TABLA EN BASE DE DATOS (IMPORTANTE)

**Si la tabla `password_resets` NO existe:**

```bash
mysql -u root -p
```
Contraseña: `Admin12345`

```sql
USE pnkcl_iot;

CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(5) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

EXIT;
```

---

### 6️⃣ PROBAR DESDE TU PC (Windows)

Abre **cmd.exe** (Win + R → cmd) y ejecuta:

```batch
cd C:\Users\savka\AndroidStudioProjects\Test
test_recuperar.bat
```

O manualmente:

```batch
curl -v -X POST -d "email=test@example.com" http://98.95.39.30/solicitar_codigo.php
```

**Si ves el JSON con el código DEBUG → ¡Listo para probar en la app!**

---

### 7️⃣ PROBAR EN LA APP ANDROID

1. Abre la app
2. Ve a **"Recuperar Contraseña"**
3. Ingresa un email que exista en tu BD (ej: `luna@gmail.com`)
4. Click en **"Solicitar Código"**
5. Deberías ver un mensaje con el código
6. Ingresa el código
7. Crea nueva contraseña

🎉 **¡DEBERÍA FUNCIONAR!**

---

## 🆘 SI NO FUNCIONA

### Ver errores en el servidor:

```bash
sudo tail -20 /var/log/php-fpm/error.log
sudo tail -20 /var/log/httpd/error_log
```

### Verificar que el archivo existe:

```bash
ls -lh /var/www/html/solicitar_codigo.php
cat /var/www/html/solicitar_codigo.php | head -20
```

### Verificar que el email existe en la BD:

```bash
mysql -u root -p
USE pnkcl_iot;
SELECT id, email FROM users;
EXIT;
```

---

## 📝 CHECKLIST RÁPIDO

```
[ ] WinSCP conectado a 98.95.39.30
[ ] solicitar_codigo_NUEVO.php subido a /home/ec2-user/
[ ] validar_codigo_NUEVO.php subido a /home/ec2-user/
[ ] SSH conectado al servidor
[ ] Archivos movidos a /var/www/html/
[ ] Permisos establecidos (chmod + chown)
[ ] Tabla password_resets creada
[ ] curl desde servidor → JSON OK
[ ] curl desde PC → JSON OK
[ ] App Android → Recuperar contraseña funciona ✅
```

---

## 🎯 RESULTADO FINAL

Cuando todo esté bien, al hacer curl verás:

```json
{
  "status": "success",
  "message": "Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 12345)"
}
```

Y en la app verás un **SweetAlert verde** con el mensaje y el código.

---

## ⏱️ TIEMPO ESTIMADO

- Subir archivos con WinSCP: **2 minutos**
- Ejecutar comandos SSH: **1 minuto**
- Crear tabla BD (si no existe): **1 minuto**
- Probar: **1 minuto**

**TOTAL: ~5 minutos** ⚡

---

## 💡 IMPORTANTE

Los archivos `solicitar_codigo_NUEVO.php` y `validar_codigo_NUEVO.php` ya están creados en tu carpeta:

```
C:\Users\savka\AndroidStudioProjects\Test\
```

Son archivos PHP **COMPLETOS** con toda la lógica necesaria. Solo necesitas subirlos.

---

¿Listo? **¡Empieza con el PASO 1!** 🚀


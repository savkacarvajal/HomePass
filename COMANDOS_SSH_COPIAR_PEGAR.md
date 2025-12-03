# 📝 COMANDOS PARA COPIAR Y PEGAR (Después de subir con WinSCP)

## 🔑 CONECTAR AL SERVIDOR

```bash
ssh -i TU_CLAVE.pem ec2-user@98.95.39.30
```

---

## 📤 DESPUÉS DE SUBIR LOS ARCHIVOS CON WINSCP

### ✅ OPCIÓN A: Script automático (recomendado)

1. Sube también el archivo `configurar_archivos.sh` con WinSCP a `/home/ec2-user/`
2. Ejecuta estos comandos:

```bash
# Dar permisos de ejecución al script
chmod +x /home/ec2-user/configurar_archivos.sh

# Ejecutar el script
/home/ec2-user/configurar_archivos.sh
```

---

### ✅ OPCIÓN B: Comandos manuales (uno por uno)

Copia y pega estos comandos en tu terminal SSH (PuTTY):

#### 1. Mover archivos
```bash
sudo mv /home/ec2-user/solicitar_codigo_NUEVO.php /var/www/html/solicitar_codigo.php
```

```bash
sudo mv /home/ec2-user/validar_codigo_NUEVO.php /var/www/html/validar_codigo.php
```

#### 2. Establecer permisos
```bash
sudo chmod 644 /var/www/html/solicitar_codigo.php
```

```bash
sudo chmod 644 /var/www/html/validar_codigo.php
```

#### 3. Establecer propietario
```bash
sudo chown apache:apache /var/www/html/solicitar_codigo.php
```

```bash
sudo chown apache:apache /var/www/html/validar_codigo.php
```

#### 4. Verificar que todo esté correcto
```bash
ls -lh /var/www/html/ | grep -E "(solicitar|validar)_codigo"
```

**Deberías ver algo como:**
```
-rw-r--r-- 1 apache apache 3.2K Nov  7 16:30 solicitar_codigo.php
-rw-r--r-- 1 apache apache 2.8K Nov  7 16:30 validar_codigo.php
```

#### 5. Ver el contenido del archivo
```bash
head -20 /var/www/html/solicitar_codigo.php
```

**Deberías ver:**
```php
<?php
// solicitar_codigo.php - VERSIÓN COMPLETA Y FUNCIONAL

// Limpiar TODA la salida previa y buffer
while (ob_get_level()) {
    ob_end_clean();
}
ob_start();
```

---

## 🗃️ VERIFICAR/CREAR LA TABLA password_resets

```bash
# Conectar a MySQL
mysql -u root -p
```

Ingresa la contraseña: `Admin12345`

Luego ejecuta estos comandos SQL:

```sql
USE pnkcl_iot;
```

```sql
SHOW TABLES LIKE 'password_resets';
```

**Si la tabla NO existe**, créala:

```sql
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(5) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Verificar que se creó:**

```sql
DESCRIBE password_resets;
```

**Deberías ver:**
```
+------------+--------------+------+-----+-------------------+
| Field      | Type         | Null | Key | Default           |
+------------+--------------+------+-----+-------------------+
| id         | int          | NO   | PRI | NULL              |
| email      | varchar(255) | NO   | MUL | NULL              |
| code       | varchar(5)   | NO   |     | NULL              |
| created_at | timestamp    | NO   | MUL | CURRENT_TIMESTAMP |
+------------+--------------+------+-----+-------------------+
```

**Salir de MySQL:**
```sql
EXIT;
```

---

## 🧪 PROBAR EL SERVIDOR (Desde el servidor)

### Prueba 1: Test de conexión
```bash
curl http://localhost/test_conexion.php
```

**Respuesta esperada:**
```json
{"status":"success","message":"¡Servidor y BD funcionando!","timestamp":"2025-11-07 16:30:00"}
```

### Prueba 2: Solicitar código
```bash
curl -X POST -d "email=test@example.com" http://localhost/solicitar_codigo.php
```

**Respuesta esperada:**
```json
{"status":"success","message":"Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 12345)"}
```

**❌ Si ves una respuesta vacía:**
```bash
# Ver errores de PHP
sudo tail -20 /var/log/php-fpm/error.log

# Ver errores de Apache
sudo tail -20 /var/log/httpd/error_log
```

---

## 🔄 REINICIAR SERVICIOS (Si es necesario)

```bash
# Reiniciar PHP-FPM
sudo systemctl restart php-fpm
```

```bash
# Reiniciar Apache
sudo systemctl restart httpd
```

```bash
# Ver estado de los servicios
sudo systemctl status php-fpm
sudo systemctl status httpd
```

---

## 🧹 LIMPIAR ARCHIVOS TEMPORALES (Opcional)

Después de verificar que todo funciona:

```bash
# Eliminar archivos _NUEVO si quedaron en /home/ec2-user
rm -f /home/ec2-user/*_NUEVO.php
```

---

## 📋 CHECKLIST COMPLETO

```
Conexión al Servidor
  └─ [ ] ssh -i clave.pem ec2-user@98.95.39.30

Subida de Archivos (WinSCP)
  ├─ [ ] solicitar_codigo_NUEVO.php subido a /home/ec2-user/
  └─ [ ] validar_codigo_NUEVO.php subido a /home/ec2-user/

Configuración en Servidor (SSH)
  ├─ [ ] Archivos movidos a /var/www/html/
  ├─ [ ] Permisos 644 establecidos
  ├─ [ ] Propietario apache:apache
  └─ [ ] Archivos verificados con ls -lh

Base de Datos
  ├─ [ ] Tabla password_resets existe
  └─ [ ] Estructura verificada con DESCRIBE

Pruebas en Servidor
  ├─ [ ] curl test_conexion.php → JSON OK
  └─ [ ] curl solicitar_codigo.php → JSON OK (con código DEBUG)

Pruebas desde PC
  └─ [ ] curl desde Windows → JSON OK

Prueba en App Android
  └─ [ ] Recuperar contraseña funciona
```

---

## 🎯 RESUMEN DE 3 PASOS

### PASO 1: WinSCP
Sube `solicitar_codigo_NUEVO.php` y `validar_codigo_NUEVO.php` a `/home/ec2-user/`

### PASO 2: SSH
```bash
ssh -i clave.pem ec2-user@98.95.39.30
sudo mv /home/ec2-user/solicitar_codigo_NUEVO.php /var/www/html/solicitar_codigo.php
sudo mv /home/ec2-user/validar_codigo_NUEVO.php /var/www/html/validar_codigo.php
sudo chmod 644 /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php
sudo chown apache:apache /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php
```

### PASO 3: PROBAR
```bash
curl -X POST -d "email=test@example.com" http://98.95.39.30/solicitar_codigo.php
```

---

## ✅ RESULTADO FINAL ESPERADO

```json
{
  "status": "success",
  "message": "Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 12345)"
}
```

**¡Si ves esto, ya está funcionando! 🎉**

Ahora abre la app Android y prueba la recuperación de contraseña.


# 🌐 Nueva Configuración de IP - HomePass

## 📡 Información del Servidor

**IP Pública:** `44.199.155.199`  
**IP Privada:** `172.31.78.62`

---

## ✅ Archivos Actualizados

Todos los archivos de la aplicación Android han sido actualizados con la nueva IP pública:

### 1. **ActLogin.kt**
- URL actualizada: `http://44.199.155.199/apiconsultausu.php`
- Función: Autenticación de usuarios

### 2. **RegistrarUsuarioActivity.kt**
- URL actualizada: `http://44.199.155.199/register.php`
- Función: Registro de nuevos usuarios

### 3. **CrearContrasenaActivity.kt**
- URL actualizada: `http://44.199.155.199/apimodificarclave.php`
- Función: Modificación de contraseña

### 4. **RecuperarContrasenaActivity.kt**
- URLs actualizadas:
  - `http://44.199.155.199/solicitar_codigo.php` (Solicitud de código)
  - `http://44.199.155.199/validar_codigo.php` (Validación de código)
- Función: Recuperación de contraseña

### 5. **ListarUsuariosActivity.kt**
- URLs actualizadas:
  - `http://44.199.155.199/get_users.php` (Obtener usuarios)
  - `http://44.199.155.199/update_user.php` (Actualizar usuario)
  - `http://44.199.155.199/delete_user.php` (Eliminar usuario)
- Función: Gestión de usuarios

---

## 🔧 Archivos PHP en el Servidor

Asegúrate de que los siguientes archivos PHP estén presentes en tu servidor:

1. ✅ `apiconsultausu.php` - Login
2. ✅ `register.php` - Registro
3. ✅ `apimodificarclave.php` - Modificar contraseña
4. ✅ `solicitar_codigo.php` - Solicitar código de recuperación
5. ✅ `validar_codigo.php` - Validar código de recuperación
6. ✅ `get_users.php` - Listar usuarios
7. ✅ `update_user.php` - Actualizar usuario
8. ✅ `delete_user.php` - Eliminar usuario
9. ✅ `conexion.php` - Conexión a la base de datos
10. ✅ `email_config.php` - Configuración de email

---

## 📋 Pasos Siguientes

### 1. Subir Archivos PHP al Nuevo Servidor
```bash
# Conectar al servidor via SSH
ssh -i tu-clave.pem ubuntu@44.199.155.199

# O usar WinSCP con la IP: 44.199.155.199
```

### 2. Configurar Base de Datos
Actualiza el archivo `conexion.php` en el servidor con las nuevas credenciales de la base de datos si es necesario.

### 3. Configurar Permisos
```bash
sudo chmod 755 /var/www/html/*.php
sudo chown www-data:www-data /var/www/html/*.php
```

### 4. Probar la Conexión
- Ejecuta: `PROBAR_SERVIDOR.bat`
- O visita: `http://44.199.155.199/test_conexion.php`

### 5. Compilar y Probar la App
1. Sincroniza el proyecto en Android Studio: `File → Sync Project with Gradle Files`
2. Compila la aplicación: `Build → Rebuild Project`
3. Ejecuta la app en un dispositivo o emulador
4. Prueba todas las funcionalidades (login, registro, recuperar contraseña, etc.)

---

## 🔐 Configuración de Seguridad

### Grupo de Seguridad AWS (Security Group)
Asegúrate de que el grupo de seguridad permita:

**Reglas de Entrada (Inbound):**
- Puerto 80 (HTTP) desde 0.0.0.0/0
- Puerto 443 (HTTPS) desde 0.0.0.0/0 (si usas SSL)
- Puerto 22 (SSH) desde tu IP (para administración)
- Puerto 3306 (MySQL) solo desde localhost o IPs específicas

---

## 📝 Notas Importantes

- ⚠️ La aplicación actualmente usa HTTP (no seguro). Considera configurar HTTPS con SSL/TLS para producción.
- 🔄 Si cambias la IP nuevamente, edita todos los archivos `.kt` mencionados arriba.
- 💾 Haz backup de tu base de datos antes de migrar.
- 🧪 Prueba todas las funcionalidades después de la migración.

---

## 🐛 Solución de Problemas

### Error de Conexión
```bash
# Verificar que Apache esté corriendo
sudo systemctl status apache2

# Reiniciar Apache si es necesario
sudo systemctl restart apache2
```

### Error de Base de Datos
```bash
# Verificar MySQL
sudo systemctl status mysql

# Revisar logs
sudo tail -f /var/log/apache2/error.log
```

### Error 404 en archivos PHP
```bash
# Verificar que los archivos estén en el directorio correcto
ls -la /var/www/html/
```

---

**Fecha de Actualización:** 2025-01-21  
**IP Anterior:** 98.95.39.30  
**IP Nueva:** 44.199.155.199


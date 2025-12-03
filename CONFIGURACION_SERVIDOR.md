# HomePass - Configuración del Servidor

## Información de la Instancia AWS

### Nueva Instancia
- **IP Pública:** `44.199.155.199`
- **IP Privada:** `172.31.78.62`
- **Fecha de creación:** 21 de noviembre de 2025

### Configuración de Base de Datos
- **Host:** `127.0.0.1` (localhost en el servidor)
- **Usuario:** `root`
- **Contraseña:** `Admin12345`
- **Base de datos:** `pnkcl_iot`

## Archivos PHP del Servidor

Los siguientes archivos PHP deben estar en el servidor AWS:

1. `apiconsultausu.php` - Login de usuarios
2. `apimodificarclave.php` - Modificar contraseña
3. `register.php` - Registro de usuarios
4. `get_users.php` - Obtener lista de usuarios
5. `update_user.php` - Actualizar datos de usuario
6. `delete_user.php` - Eliminar usuario
7. `solicitar_codigo.php` - Solicitar código de recuperación
8. `validar_codigo.php` - Validar código de recuperación
9. `conexion.php` - Archivo de conexión a la base de datos
10. `email_config.php` - Configuración de email (PHPMailer)

## URLs de la API

Todas las URLs de la API usan la IP pública:

```
http://44.199.155.199/apiconsultausu.php
http://44.199.155.199/apimodificarclave.php
http://44.199.155.199/register.php
http://44.199.155.199/get_users.php
http://44.199.155.199/update_user.php
http://44.199.155.199/delete_user.php
http://44.199.155.199/solicitar_codigo.php
http://44.199.155.199/validar_codigo.php
```

## Archivos de la App Android que Contienen la IP

Los siguientes archivos Kotlin contienen las URLs del servidor:

1. `ActLogin.kt` - Login
2. `CrearContrasenaActivity.kt` - Crear contraseña
3. `ListarUsuariosActivity.kt` - Listar y editar usuarios
4. `RecuperarContrasenaActivity.kt` - Recuperar contraseña
5. `RegistrarUsuarioActivity.kt` - Registrar usuario

## Cambiar la IP en el Futuro

Si necesitas cambiar la IP del servidor en el futuro:

1. Busca todos los archivos `.kt` que contengan `http://44.199.155.199`
2. Reemplaza con la nueva IP
3. Recompila la aplicación
4. Actualiza los archivos PHP en el nuevo servidor

## Estructura del Proyecto

```
HomePass/
├── app/                          # Módulo principal de Android
│   └── src/main/java/com/example/test/
│       ├── ActLogin.kt
│       ├── CrearContrasenaActivity.kt
│       ├── ListarUsuariosActivity.kt
│       ├── RecuperarContrasenaActivity.kt
│       └── RegistrarUsuarioActivity.kt
├── homepass/                     # Módulo secundario
└── Archivos PHP del servidor/
    ├── apiconsultausu.php
    ├── apimodificarclave.php
    ├── conexion.php
    └── ...
```

## Comandos Útiles

### Conectarse al servidor AWS por SSH:
```bash
ssh -i "tu-clave.pem" ubuntu@44.199.155.199
```

### Subir archivos con WinSCP:
- Host: `44.199.155.199`
- Protocol: `SFTP`
- User: `ubuntu`
- Private key: Tu archivo `.pem`

### Verificar que Apache está corriendo:
```bash
sudo systemctl status apache2
```

### Verificar que MySQL está corriendo:
```bash
sudo systemctl status mysql
```


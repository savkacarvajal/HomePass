# 🏠 HomePass IoT - Sistema de Gestión y Control de Acceso

<div align="center">

![Android](https://img.shields.io/badge/Android-3DDC84?style=for-the-badge&logo=android&logoColor=white)
![Kotlin](https://img.shields.io/badge/Kotlin-0095D5?style=for-the-badge&logo=kotlin&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![IoT](https://img.shields.io/badge/IoT-00979D?style=for-the-badge&logo=arduino&logoColor=white)
![Material Design](https://img.shields.io/badge/Material%20Design-757575?style=for-the-badge&logo=material-design&logoColor=white)

**Aplicación Android completa para gestión de usuarios y control de acceso IoT con backend PHP y MySQL**

[Características](#-características) • [Instalación](#-instalación) • [Uso](#-uso) • [API](#-api-backend) • [Documentación](#-documentación-adicional)

</div>

---

## 📋 Descripción

Sistema integral **HomePass IoT** de gestión de usuarios y control de acceso desarrollado en **Kotlin** para Android, con backend en **PHP** y base de datos **MySQL**. La aplicación incluye autenticación completa, CRUD de usuarios, recuperación de contraseña por email (funcionando con cualquier proveedor), gestión de sensores IoT y una interfaz moderna basada en Material Design.

**Estado del Proyecto:** ✅ Completado y Funcional | **Última Actualización:** Diciembre 2025

## ✨ Características

### 🔐 Autenticación y Seguridad
- **Splash Screen** animado con Lottie
- **Sistema de Login** con validación en tiempo real
- **Registro de usuarios** con validaciones robustas
- **Recuperación de contraseña** ✅ Funcional - Envía códigos por email a cualquier proveedor (Gmail, INACAP, Outlook, etc.)
- **Email HTML profesional** con códigos de 5 dígitos y expiración de 15 minutos
- **Contraseñas cifradas** con bcrypt para máxima seguridad

### 👥 Gestión de Usuarios
- **Listado completo** de usuarios con RecyclerView
- **Búsqueda en tiempo real** por nombre o apellidos
- **CRUD completo**: Crear, Leer, Actualizar y Eliminar usuarios
- **Validación de datos** (email, contraseñas, campos requeridos)
- **Diálogos elegantes** con SweetAlert para confirmaciones

### 📊 Funcionalidades Adicionales
- **Panel de sensores** para monitoreo IoT
- **Información del desarrollador** con datos del proyecto
- **Actualización de fecha y hora** en tiempo real
- **Base de datos local** con Room (SQLite) para caché offline
- **Manejo de estados vacíos** y mensajes de error

### 🎨 Interfaz de Usuario
- **Material Design 3** con componentes modernos
- **View Binding** para acceso seguro a vistas
- **RecyclerView** con adaptadores personalizados
- **TextInputLayout** con validaciones visuales
- **Animaciones fluidas** con Lottie
- **Diseño responsive** adaptable a diferentes tamaños de pantalla

## 🛠️ Tecnologías

### Frontend (Android)
- **Lenguaje**: Kotlin
- **SDK Mínimo**: Android 7.0 (API 24)
- **SDK Target**: Android 14 (API 36)
- **Build Tool**: Gradle (Kotlin DSL)
- **Arquitectura**: MVVM con View Binding

### Librerías Principales
```kotlin
// Networking
implementation("com.android.volley:volley:1.2.1")

// Animaciones
implementation("com.airbnb.android:lottie:6.4.0")

// Diálogos
implementation("com.github.f0ris.sweetalert:library:1.6.2")

// Base de datos local
implementation("androidx.room:room-runtime:2.x.x")

// Material Design
implementation("com.google.android.material:material:1.x.x")

// Lifecycle
implementation("androidx.lifecycle:lifecycle-runtime-ktx:2.7.0")
```

### Backend
- **Lenguaje**: PHP 7.4+
- **Base de datos**: MySQL 5.7+ / MariaDB
- **Email**: PHPMailer para envío de códigos de recuperación
- **API**: RESTful con respuestas JSON

## 📦 Instalación

### Prerrequisitos

- **Android Studio** Otter | 2025.2.1 Patch 1 o superior
- **JDK 11** o superior
- **Servidor PHP** (XAMPP, WAMP, Laragon, o servidor remoto)
- **MySQL/MariaDB** 5.7+
- **Git** para clonar el repositorio

### 1️⃣ Clonar el Repositorio

```bash
git clone https://github.com/tuusuario/gestion-usuarios-android.git
cd gestion-usuarios-android
```

### 2️⃣ Configurar Backend (PHP + MySQL)

#### Configurar Base de Datos

1. Importar el script SQL:
```sql
-- Ejecutar crear_tabla_codigos.sql en tu base de datos
-- El script creará las tablas necesarias: usuarios, codigos_recuperacion, etc.
```

2. Configurar conexión en `conexion.php`:
```php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', 'tu_contraseña');
define('DB_NAME', 'pnkcl_iot');
```

#### Configurar Email (PHPMailer)

> ✅ **Estado:** Configurado y funcional. Envía a cualquier proveedor de email.

1. Instalar PHPMailer:
```bash
composer require phpmailer/phpmailer
```

2. Configurar `email_config.php` (ya configurado):
```php
// Gmail SMTP - Requiere contraseña de aplicación
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'tu_email@gmail.com');
define('SMTP_PASSWORD', 'contraseña_app_16_chars');
```

**Documentación completa:** Ver `SISTEMA_RECUPERACION_PASSWORD.md`

#### Subir Archivos PHP al Servidor

Opción A - Servidor Local:
- Copiar todos los archivos `.php` a tu carpeta `htdocs` o `www`

Opción B - Servidor Remoto:
- Usar WinSCP, FileZilla o FTP para subir archivos
- Ver `GUIA_WINSCP_COMPLETA.txt` para instrucciones detalladas

### 3️⃣ Configurar Aplicación Android

1. Abrir el proyecto en Android Studio:
```
File > Open > Seleccionar carpeta del proyecto
```

2. Esperar sincronización de Gradle

3. Configurar URL del servidor en las Activities:
```kotlin
// En cada Activity que use Volley, actualizar:
private val BASE_URL = "http://tu-servidor.com/api/" // o http://10.0.2.2/ para emulador local
```

4. Sincronizar proyecto:
```
File > Sync Project with Gradle Files
```

### 4️⃣ Ejecutar la Aplicación

#### En Emulador
```powershell
# Desde Android Studio: Run > Run 'app'
# O desde terminal:
.\gradlew.bat assembleDebug
.\gradlew.bat installDebug
```

#### En Dispositivo Físico
1. Activar **Depuración USB** en el dispositivo
2. Conectar por USB
3. Ejecutar desde Android Studio

> **Nota para localhost**: Si usas servidor local (XAMPP), el emulador usa `10.0.2.2` para acceder a `localhost` de tu PC.

## 📱 Uso

### Flujo de la Aplicación

1. **Splash Screen** → Animación de carga (7 segundos)
2. **Login** → Autenticación con usuario y contraseña
   - ¿Olvidaste tu contraseña? → Recuperación por email
   - ¿No tienes cuenta? → Registro de usuario
3. **Menú Principal** → Tres opciones:
   - 👥 **Gestión de Usuarios** → CRUD completo
   - 📊 **Sensores** → Monitoreo IoT
   - 👨‍💻 **Desarrollador** → Información del proyecto

### Gestión de Usuarios

#### Listar Usuarios
- Visualiza todos los usuarios registrados
- Búsqueda en tiempo real por nombre/apellidos
- Click en usuario para ver opciones

#### Agregar Usuario
- FAB (+) para nuevo usuario
- Validación de email único
- Confirmación de contraseña

#### Editar Usuario
- Click en usuario → "Modificar"
- Actualización de datos
- Validación en tiempo real

#### Eliminar Usuario
- Click en usuario → "Eliminar"
- Confirmación con SweetAlert
- Eliminación permanente

## 🌐 API Backend

### Endpoints Disponibles

#### 📍 Autenticación

**POST** `/login.php`
```json
// Request
{
  "email": "usuario@example.com",
  "password": "contraseña123"
}

// Response
{
  "status": "success",
  "message": "Login exitoso",
  "user": {
    "id": 1,
    "nombre": "Juan",
    "apellidos": "Pérez",
    "email": "usuario@example.com"
  }
}
```

#### 📍 Usuarios

**GET** `/listar_usuarios.php`
```json
// Response
[
  {
    "id": 1,
    "nombre": "Juan",
    "apellidos": "Pérez García",
    "email": "juan@example.com",
    "fecha_creacion": "2025-01-15"
  }
]
```

**POST** `/registrar_usuario.php`
```json
// Request
{
  "nombre": "María",
  "apellidos": "López",
  "email": "maria@example.com",
  "password": "contraseña123"
}
```

**PUT** `/modificar_usuario.php`
```json
// Request
{
  "id": 1,
  "nombre": "Juan Carlos",
  "apellidos": "Pérez García",
  "email": "juanc@example.com"
}
```

**DELETE** `/eliminar_usuario.php?id=1`

#### 📍 Recuperación de Contraseña ✅

**POST** `/solicitar_codigo_con_email.php`
```json
// Request
{
  "email": "usuario@example.com"
}

// Response
{
  "status": "success",
  "message": "Código enviado a tu correo electrónico"
}
// ✅ Envía email HTML a cualquier proveedor (Gmail, INACAP, Outlook, etc.)
```

**POST** `/validar_codigo.php`
```json
// Request
{
  "email": "usuario@example.com",
  "code": "12345"
}

// Response
{
  "status": "success",
  "message": "Código válido"
}
```

**POST** `/apimodificarclave.php`
```json
// Request
{
  "email": "usuario@example.com",
  "nuevaclave": "nuevaContraseña123"
}

// Response
{
  "status": "success",
  "message": "Contraseña actualizada exitosamente"
}
```

> 📖 **Documentación detallada:** Ver `SISTEMA_RECUPERACION_PASSWORD.md`

## 🏗️ Estructura del Proyecto

```
Test/
├── 📱 app/
│   ├── src/
│   │   ├── main/
│   │   │   ├── java/com/example/test/
│   │   │   │   ├── ActLogin.kt                  # Pantalla de login
│   │   │   │   ├── SplashActivity.kt           # Splash screen
│   │   │   │   ├── MainActivity.kt             # Menú principal
│   │   │   │   ├── RegistrarUsuarioActivity.kt # Registro
│   │   │   │   ├── RecuperarContrasenaActivity.kt # Recuperación
│   │   │   │   ├── CrearContrasenaActivity.kt  # Nueva contraseña
│   │   │   │   ├── GestionUsuarioActivity.kt   # Gestión usuarios
│   │   │   │   ├── ListarUsuariosActivity.kt   # Listado con búsqueda
│   │   │   │   ├── SensoresActivity.kt         # Panel sensores
│   │   │   │   ├── DesarrolladorActivity.kt    # Info desarrollador
│   │   │   │   ├── User.kt                     # Modelo de datos
│   │   │   │   ├── UserAdapter.kt              # Adaptador RecyclerView
│   │   │   │   ├── AppDatabase.kt              # Base datos Room
│   │   │   │   ├── UserDao.kt                  # DAO Room
│   │   │   │   └── ConexionDbHelper.kt         # Helper SQLite
│   │   │   ├── res/
│   │   │   │   ├── layout/
│   │   │   │   │   ├── activity_main.xml
│   │   │   │   │   ├── activity_listar_usuarios.xml
│   │   │   │   │   └── list_item_user.xml      # Item de lista
│   │   │   │   ├── drawable/
│   │   │   │   └── values/
│   │   │   └── AndroidManifest.xml
│   │   ├── androidTest/
│   │   └── test/
│   └── build.gradle.kts
│
├── 🌐 Backend PHP/
│   ├── conexion.php                    # Configuración BD
│   ├── email_config.php                # Configuración email
│   ├── login.php                       # API login
│   ├── registrar_usuario.php           # API registro
│   ├── listar_usuarios.php             # API listado
│   ├── modificar_usuario.php           # API actualización
│   ├── eliminar_usuario.php            # API eliminación
│   ├── solicitar_codigo.php            # API código recuperación
│   ├── validar_codigo.php              # API validar código
│   ├── apimodificarclave.php           # API cambiar contraseña
│   └── crear_tabla_codigos.sql         # Script BD
│
├── 📚 Documentación/
│   ├── GUIA_CONFIGURAR_EMAIL.md
│   ├── GUIA_WINSCP_COMPLETA.txt
│   ├── SOLUCION_ERROR_RECUPERAR.md
│   └── [otros archivos de ayuda]
│
├── build.gradle.kts
├── settings.gradle.kts
└── README.md
```

## 🎨 Capturas

> **Nota**: Agrega aquí capturas de pantalla de tu aplicación

```markdown
### Splash Screen
![Splash](screenshots/splash.png)

### Login
![Login](screenshots/login.png)

### Menú Principal
![Menu](screenshots/menu.png)

### Listado de Usuarios
![Listado](screenshots/listado.png)

### Gestión de Usuarios
![CRUD](screenshots/crud.png)
```

## 🔧 Configuración Avanzada

### Personalizar Tiempo de Splash

En `SplashActivity.kt`:
```kotlin
private val SPLASH_TIME_OUT: Long = 7000 // 7 segundos (modificar a tu preferencia)
```

### Cambiar URL Base del API

Buscar en cada Activity con Volley:
```kotlin
private val BASE_URL = "http://tu-dominio.com/api/"
```

### Configurar Servidor Local para Emulador

```kotlin
// Para emulador Android Studio (apunta a localhost de tu PC)
private val BASE_URL = "http://10.0.2.2/api/"

// Para dispositivo físico en misma red WiFi
private val BASE_URL = "http://192.168.1.X/api/" // IP de tu PC
```

## 🧪 Testing

### Probar Conexión Backend

```powershell
# Test de conexión a BD
.\PROBAR_SERVIDOR.bat

# Test de email
.\PROBAR_EMAIL.bat

# Test de crear contraseña
.\PROBAR_CREAR_CONTRASENA.bat
```

### Tests Unitarios

```powershell
.\gradlew.bat test
```

### Tests Instrumentados

```powershell
.\gradlew.bat connectedAndroidTest
```

## 🐛 Solución de Problemas

### Error: No se conecta al servidor

**Problema**: App no puede conectar con backend PHP

**Soluciones**:
1. Verificar que el servidor PHP esté ejecutándose
2. Comprobar la URL en el código Android
3. Para emulador, usar `10.0.2.2` en lugar de `localhost`
4. Verificar permisos de INTERNET en `AndroidManifest.xml`
5. En servidor local, asegurar que `usesCleartextTraffic="true"`

### Error: Email no se envía

**Problema**: Código de recuperación no llega al email

**Soluciones**:
1. Verificar configuración SMTP en `email_config.php`
2. Para Gmail, generar "Contraseña de aplicación"
3. Comprobar que PHPMailer esté instalado
4. Revisar logs del servidor PHP
5. Consultar `GUIA_CONFIGURAR_EMAIL.md`

### Error: Base de datos vacía

**Problema**: No se muestran usuarios

**Soluciones**:
1. Ejecutar `crear_tabla_codigos.sql`
2. Verificar credenciales en `conexion.php`
3. Comprobar que la BD `pnkcl_iot` exista
4. Insertar usuarios de prueba manualmente

### Error de Build en Gradle

**Problema**: Fallos al compilar

**Soluciones**:
```powershell
# Limpiar proyecto
.\gradlew.bat clean

# Invalidar cachés
# File > Invalidate Caches / Restart en Android Studio

# Sincronizar Gradle
.\gradlew.bat --refresh-dependencies
```

## 📚 Documentación Adicional

Documentación técnica disponible en el repositorio:

- 📧 **SISTEMA_RECUPERACION_PASSWORD.md** - Sistema completo de recuperación con email ✅
- 🔐 **RESPUESTA_COMPLETA_ADMIN_RECUPERACION.md** - Gestión de usuarios y recuperación
- 📋 **crear_base_datos_completa.sql** - Script completo de base de datos
- 🧪 **test_envio_simple.php** - Script de prueba de emails
- 🔧 **NodeMCU_HomePass_IoT.ino** - Código para sensores IoT

### Estado de Funcionalidades

| Funcionalidad | Estado | Documentación |
|---------------|--------|---------------|
| Autenticación | ✅ Funcional | README.md |
| CRUD Usuarios | ✅ Funcional | README.md |
| Recuperación Password | ✅ Funcional | SISTEMA_RECUPERACION_PASSWORD.md |
| Email a cualquier proveedor | ✅ Probado | SISTEMA_RECUPERACION_PASSWORD.md |
| Sensores IoT | ✅ Funcional | NodeMCU_HomePass_IoT.ino |
| Base de Datos | ✅ Funcional | crear_base_datos_completa.sql |

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Por favor, sigue estos pasos:

1. **Fork** el proyecto
2. Crea una **rama** para tu feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** tus cambios (`git commit -m 'Add: Amazing Feature'`)
4. **Push** a la rama (`git push origin feature/AmazingFeature`)
5. Abre un **Pull Request**

### Guía de Estilo

- Usar **Kotlin** para código Android
- Seguir convenciones de **Material Design**
- Documentar funciones públicas
- Escribir tests para nueva funcionalidad
- Commits en español o inglés, descriptivos

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

```
MIT License

Copyright (c) 2025 Salvador Carvajal

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction...
```

## 👨‍💻 Autores

**Savka Carvajal & Dante Gutierrez**

- Proyecto: HomePass IoT
- Institución: INACAP 2025
- Materia: Aplicaciones Móviles para IoT

## 🙏 Agradecimientos

- [Material Design](https://material.io/) por los componentes UI
- [Lottie](https://airbnb.design/lottie/) por las animaciones
- [SweetAlert](https://github.com/F0RIS/sweet-alert-dialog) por los diálogos elegantes
- [Volley](https://github.com/google/volley) por el networking
- [PHPMailer](https://github.com/PHPMailer/PHPMailer) por el envío de emails
- Comunidad de Android Developers
- INACAP por el apoyo académico

## 📊 Estado del Proyecto

![Status](https://img.shields.io/badge/status-active-success.svg)
![Build](https://img.shields.io/badge/build-passing-brightgreen.svg)
![Version](https://img.shields.io/badge/version-1.0-blue.svg)

**Versión Actual**: 1.0  
**Última Actualización**: Diciembre 2025  
**Estado**: ✅ Completado y en Producción  
**Pruebas**: ✅ Sistema de email probado con múltiples proveedores

---

<div align="center">

**⭐ Si este proyecto te fue útil, considera darle una estrella ⭐**

[⬆ Volver arriba](#-sistema-de-gestión-de-usuarios---android--php)

</div>


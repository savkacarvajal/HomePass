# 🔒 CONFIGURACIÓN SEGURA - HomePass IoT

## ⚠️ IMPORTANTE: CREDENCIALES PRIVADAS

Los archivos `email_config.php` y `conexion.php` contienen credenciales sensibles y **NO deben subirse a Git**.

---

## 📋 INSTRUCCIONES DE CONFIGURACIÓN

### 1. Configurar Email (email_config.php)

**Copia el archivo de ejemplo:**
```bash
cp email_config.example.php email_config.php
```

**Edita `email_config.php` con tus credenciales:**
```php
define('SMTP_USERNAME', 'TU_EMAIL@gmail.com'); // ⚠️ CAMBIAR
define('SMTP_PASSWORD', 'tu_contraseña_app'); // ⚠️ CAMBIAR
define('FROM_EMAIL', 'TU_EMAIL@gmail.com');    // ⚠️ CAMBIAR
```

**Para Gmail:**
1. Activa la verificación en 2 pasos: https://myaccount.google.com/security
2. Genera contraseña de aplicación: https://myaccount.google.com/apppasswords
3. Usa esa contraseña de 16 caracteres

---

### 2. Configurar Base de Datos (conexion.php)

**Copia el archivo de ejemplo:**
```bash
cp conexion.example.php conexion.php
```

**Edita `conexion.php` con tus credenciales:**
```php
define('DB_HOST', 'localhost');      // Tu servidor
define('DB_USER', 'root');           // ⚠️ CAMBIAR
define('DB_PASS', 'TU_CONTRASEÑA');  // ⚠️ CAMBIAR
define('DB_NAME', 'homepass_db');    // Tu base de datos
```

---

## 🔐 SEGURIDAD

### ✅ Archivos Protegidos en .gitignore

```gitignore
# Archivos con credenciales - NO SUBIR
email_config.php
conexion.php
```

### ✅ Archivos de Ejemplo (SÍ se suben a Git)

```
✅ email_config.example.php    → Plantilla sin credenciales
✅ conexion.example.php        → Plantilla sin credenciales
```

---

## 🚨 SI YA SUBISTE CREDENCIALES A GIT

### Opción 1: Cambiar Credenciales (RECOMENDADO)

1. **Gmail:**
   - Revoca la contraseña de aplicación actual
   - Genera una nueva en: https://myaccount.google.com/apppasswords
   
2. **Base de Datos:**
   - Cambia la contraseña de tu usuario MySQL
   ```sql
   ALTER USER 'root'@'localhost' IDENTIFIED BY 'NUEVA_CONTRASEÑA_SEGURA';
   ```

### Opción 2: Limpiar Historial de Git (AVANZADO)

⚠️ **Advertencia:** Esto reescribirá el historial de Git

```bash
# Eliminar archivo del historial
git filter-branch --force --index-filter \
  "git rm --cached --ignore-unmatch email_config.php conexion.php" \
  --prune-empty --tag-name-filter cat -- --all

# Forzar push (⚠️ CUIDADO)
git push origin --force --all
```

---

## 📝 CHECKLIST DE SEGURIDAD

Antes de hacer push a GitHub:

- [ ] `email_config.php` está en .gitignore
- [ ] `conexion.php` está en .gitignore
- [ ] Creaste `email_config.example.php` (sin credenciales)
- [ ] Creaste `conexion.example.php` (sin credenciales)
- [ ] Verificaste que no hay contraseñas en el código
- [ ] Cambiaste las credenciales expuestas

---

## 🛡️ MEJORES PRÁCTICAS

### Para Desarrollo Local
```php
// Usar archivos .php separados con credenciales locales
require_once 'email_config.php';  // Este NO se sube a Git
```

### Para Producción
```php
// Usar variables de entorno
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD'));
define('DB_PASS', getenv('DB_PASSWORD'));
```

---

## 📚 DOCUMENTACIÓN ADICIONAL

- [Contraseñas de aplicación Gmail](https://support.google.com/accounts/answer/185833)
- [Seguridad en Git](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure)
- [Variables de entorno PHP](https://www.php.net/manual/es/function.getenv.php)

---

**Desarrollado por:** Savka Carvajal & Dante Gutierrez  
**Proyecto:** HomePass IoT - INACAP 2025


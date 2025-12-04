# 📧 Sistema de Recuperación de Contraseña - HomePass IoT

## ✅ Estado: COMPLETADO Y FUNCIONAL

**Última actualización:** 3 de diciembre de 2025  
**Prueba exitosa:** Email enviado a savka.carvajal@inacapmail.cl ✅

---

## 🎯 Funcionalidades

### ✅ Envío de Códigos por Email
- **Destinatarios:** Cualquier proveedor (Gmail, INACAP, Outlook, Yahoo, etc.)
- **Formato:** Email HTML profesional
- **Códigos:** 5 dígitos aleatorios
- **Expiración:** 15 minutos
- **SMTP:** Gmail (smtp.gmail.com:587)

### ✅ Validación y Cambio de Contraseña
- Validación de códigos con expiración
- Cambio seguro de contraseña (bcrypt)
- Prepared statements (SQL injection protection)

---

## 📁 Archivos del Sistema

### Backend PHP (en servidor)
```
/var/www/html/
├── solicitar_codigo_con_email.php   # Genera y envía código
├── validar_codigo.php                # Valida código y expiración
├── apimodificarclave.php             # Cambia contraseña
├── email_config.php                  # Configuración SMTP
└── conexion.php                      # Conexión a BD
```

### Base de Datos
```sql
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(5) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP + INTERVAL 15 MINUTE)
);
```

---

## 🚀 Uso desde la App Android

### 1. Solicitar Código
```kotlin
// POST a solicitar_codigo_con_email.php
val params = mapOf("email" to userEmail)

// Respuesta:
// {"status": "success", "message": "Código enviado a tu correo"}
```

### 2. Validar Código
```kotlin
// POST a validar_codigo.php
val params = mapOf(
    "email" to userEmail,
    "code" to userCode
)

// Respuesta:
// {"status": "success", "message": "Código válido"}
```

### 3. Cambiar Contraseña
```kotlin
// POST a apimodificarclave.php
val params = mapOf(
    "email" to userEmail,
    "nuevaclave" to newPassword
)

// Respuesta:
// {"status": "success", "message": "Contraseña actualizada"}
```

---

## 🔧 Configuración SMTP (email_config.php)

```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'savkacarvajalg1@gmail.com');
define('SMTP_PASSWORD', 'zjewbamfuzzftmbi'); // Contraseña de aplicación
define('FROM_EMAIL', 'savkacarvajalg1@gmail.com');
define('FROM_NAME', 'HomePass IoT');
```

### Requisitos:
- PHPMailer instalado: `composer require phpmailer/phpmailer`
- Contraseña de aplicación Gmail (2FA activado)
- Generar en: https://myaccount.google.com/apppasswords

---

## 🧪 Pruebas

### Probar envío de email:
```
http://44.199.155.199/test_envio_simple.php?email=TU_EMAIL
```

### Ver código temporal (solo desarrollo):
```
http://44.199.155.199/ver_codigo_temporal.php?email=EMAIL_USUARIO
```

---

## 🔒 Seguridad

- ✅ Códigos aleatorios (100,000 combinaciones)
- ✅ Expiración automática (15 min)
- ✅ Prepared statements (SQL injection protection)
- ✅ Sanitización de inputs
- ✅ Contraseñas cifradas con bcrypt
- ✅ Mensaje genérico si email no existe (privacidad)

---

## 📊 Pruebas Realizadas

| Email | Proveedor | Resultado | Fecha |
|-------|-----------|-----------|-------|
| savka.carvajal@inacapmail.cl | INACAP | ✅ Exitoso | 2025-12-03 |
| savkacarvajalg1@gmail.com | Gmail | ✅ Funciona | 2025-12-03 |

**Conclusión:** El sistema envía a cualquier proveedor de email sin restricciones.

---

## 🐛 Troubleshooting

### Error: "SMTP Could not authenticate"
**Solución:**
1. Verifica que la verificación en 2 pasos esté activa en Gmail
2. Genera nueva contraseña de aplicación
3. Actualiza `SMTP_PASSWORD` en `email_config.php`

### Error: "PHPMailer not found"
**Solución:**
```bash
ssh ec2-user@44.199.155.199
cd /var/www/html
composer require phpmailer/phpmailer
```

### Email no llega
**Verificar:**
- ✅ Carpeta de SPAM
- ✅ Email escrito correctamente
- ✅ Usuario existe en la base de datos
- ✅ Probar con: `test_envio_simple.php`

---

## 📝 Notas de Producción

- **Límite Gmail:** 500 emails/día (cuenta gratuita)
- **Alternativas:** SendGrid (100/día gratis), Amazon SES, Mailgun
- **Monitoreo:** Revisar logs en servidor para errores SMTP
- **Backup:** Mantener contraseña de app en lugar seguro

---

**Desarrollado por:** Savka Carvajal & Dante Gutierrez  
**Proyecto:** HomePass IoT - Aplicaciones Móviles para IoT  
**Institución:** INACAP 2025


# 📧 CONFIGURACIÓN DE ENVÍO DE EMAILS - RESUMEN EJECUTIVO

## 🎯 OBJETIVO
Enviar códigos de recuperación de contraseña por email en lugar de mostrarlos en la app.

---

## 📦 ARCHIVOS CREADOS

### ✅ Archivos PHP para el servidor:
1. **solicitar_codigo_EMAIL.php** - Versión simple con mail()
2. **solicitar_codigo_SMTP.php** - Versión profesional con PHPMailer
3. **email_config.php** - Configuración de credenciales SMTP
4. **test_email.php** - Archivo para probar el envío

### ✅ Scripts de ayuda:
5. **SUBIR_ARCHIVOS_EMAIL.bat** - Guía para subir archivos
6. **PROBAR_EMAIL.bat** - Probar envío de email desde Windows
7. **instalar_phpmailer_completo.sh** - Script para instalar en el servidor
8. **GUIA_CONFIGURAR_EMAIL.md** - Guía detallada completa

---

## ⚡ INICIO RÁPIDO - OPCIÓN SIMPLE

### 1️⃣ Sube el archivo básico
```
WinSCP → Conectar a 98.95.39.30
Arrastra: solicitar_codigo_EMAIL.php
Sobre: solicitar_codigo.php (sobrescribir)
```

### 2️⃣ Prueba
```cmd
PROBAR_SERVIDOR.bat
```

### 3️⃣ Verifica
- El código ya no se muestra en el JSON
- Busca el email en tu bandeja (y SPAM)

> ⚠️ **NOTA:** Esta opción puede enviar emails a SPAM. Para producción, usa la Opción Profesional.

---

## 🎓 INICIO RÁPIDO - OPCIÓN PROFESIONAL (RECOMENDADA)

### 1️⃣ Instala PHPMailer en el servidor
```bash
# Conecta por SSH:
ssh ec2-user@98.95.39.30 -i tu_clave.ppk

# Ejecuta:
cd /var/www/html
composer require phpmailer/phpmailer
```

### 2️⃣ Configura tus credenciales de email

Edita `email_config.php`:

**Para Gmail:**
```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'tu_email@gmail.com');
define('SMTP_PASSWORD', 'xxxx xxxx xxxx xxxx'); // Contraseña de aplicación
```

📝 **Obtener contraseña de aplicación de Gmail:**
1. https://myaccount.google.com/security
2. Activa "Verificación en 2 pasos"
3. https://myaccount.google.com/apppasswords
4. Crea contraseña para "Correo"
5. Copia los 16 caracteres

**Para SendGrid (Mejor para producción):**
```php
define('SMTP_HOST', 'smtp.sendgrid.net');
define('SMTP_USERNAME', 'apikey');
define('SMTP_PASSWORD', 'TU_API_KEY');
```

### 3️⃣ Sube los archivos
```
WinSCP → Conectar a 98.95.39.30

Sube:
  - email_config.php → /var/www/html/
  - solicitar_codigo_SMTP.php → /var/www/html/solicitar_codigo.php
  - test_email.php → /var/www/html/
```

### 4️⃣ Prueba el sistema
```cmd
# Probar envío de email
PROBAR_EMAIL.bat

# O directamente:
curl -X POST -d "email=tu_email@gmail.com" http://98.95.39.30/test_email.php
```

### 5️⃣ Verifica tu email
- ✅ Busca en Bandeja de entrada
- ✅ Busca en SPAM
- ✅ Busca en Promociones (Gmail)

---

## 🎨 DISEÑO DEL EMAIL

El email que reciben los usuarios tiene:
- ✅ Diseño profesional con HTML
- ✅ Código grande y visible: **12345**
- ✅ Advertencias de seguridad
- ✅ Información de expiración (15 minutos)
- ✅ Mensaje corporativo de PNKCL IoT

---

## 🔧 COMANDOS ÚTILES

### Ver logs del servidor
```bash
ssh ec2-user@98.95.39.30
sudo tail -f /var/log/php-fpm/error.log
```

### Probar envío de email
```bash
curl -X POST -d "email=luna@gmail.com" http://98.95.39.30/solicitar_codigo.php
```

### Verificar si PHPMailer está instalado
```bash
ssh ec2-user@98.95.39.30
ls -la /var/www/html/vendor/phpmailer/
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### ❌ Email no llega
1. Revisa SPAM
2. Verifica credenciales en `email_config.php`
3. Para Gmail, usa "Contraseña de aplicación"
4. Revisa logs: `sudo tail -f /var/log/php-fpm/error.log`

### ❌ Error "SMTP connect() failed"
- Puerto 587 o 465 debe estar abierto en el firewall
- Verifica que SMTP_HOST sea correcto
- Prueba con SendGrid (más confiable)

### ❌ Error "Authentication failed"
- Credenciales incorrectas
- Para Gmail, necesitas activar 2FA y crear "Contraseña de aplicación"
- Para Outlook, puede requerir permisos especiales

### ❌ Email va a SPAM
- Usa SendGrid o servicio profesional
- Configura SPF y DKIM (requiere dominio propio)
- Usa `From:` con dominio verificado

---

## 📊 COMPARACIÓN RÁPIDA

| Aspecto | mail() Simple | PHPMailer + Gmail | PHPMailer + SendGrid |
|---------|---------------|-------------------|---------------------|
| Configuración | 5 min | 15 min | 20 min |
| Confiabilidad | 50% | 90% | 99% |
| Va a SPAM | Mucho | Poco | Casi nunca |
| Límite diario | Ilimitado | ~500 | 100 gratis |
| Costo | Gratis | Gratis | Gratis (100/día) |
| **RECOMENDADO** | ❌ Solo pruebas | ✅ Desarrollo | ✅✅ Producción |

---

## ✅ CHECKLIST FINAL

### Para poner en producción:

- [ ] PHPMailer instalado en el servidor
- [ ] email_config.php configurado con credenciales válidas
- [ ] solicitar_codigo_SMTP.php subido como solicitar_codigo.php
- [ ] test_email.php probado exitosamente
- [ ] Email de prueba recibido correctamente
- [ ] Cambiar `define('EMAIL_DEBUG', false);` en email_config.php
- [ ] Quitar líneas de debug del código (DEBUG: código)
- [ ] Probar con email real del usuario
- [ ] Verificar que el código expira en 15 minutos

---

## 🚀 SIGUIENTE NIVEL

### Mejoras futuras:
1. **Email transaccional personalizado** con tu dominio
2. **Plantillas HTML** profesionales
3. **Registro de emails enviados** en BD
4. **Notificaciones adicionales:**
   - Bienvenida al registrarse
   - Contraseña cambiada exitosamente
   - Intento de inicio de sesión sospechoso
5. **SMS como alternativa** (Twilio, AWS SNS)

---

## 📞 SOPORTE

Si tienes problemas:
1. Revisa `GUIA_CONFIGURAR_EMAIL.md` (guía detallada)
2. Ejecuta `test_email.php` para diagnosticar
3. Revisa los logs del servidor
4. Verifica las credenciales en `email_config.php`

---

## 🎉 ¡YA ESTÁ!

Una vez configurado, tu app enviará automáticamente los códigos de recuperación por email. El usuario ya no verá el código en pantalla, lo recibirá en su correo electrónico. 📧✨


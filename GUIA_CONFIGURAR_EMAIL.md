# 📧 GUÍA COMPLETA: CONFIGURAR ENVÍO DE EMAILS

## 🎯 Objetivo
Configurar el sistema para enviar códigos de recuperación por email en lugar de mostrarlos en pantalla.

---

## 📋 OPCIÓN 1: Usando mail() (MÁS SIMPLE)

### ✅ Ventajas:
- No requiere instalación adicional
- Funciona con la configuración básica de PHP

### ⚠️ Desventajas:
- Requiere que el servidor tenga configurado sendmail o similar
- Los emails pueden ir a SPAM
- No funciona en localhost

### 📝 Pasos:

1. **Subir el archivo al servidor**
   ```
   - Abre WinSCP
   - Conecta a 98.95.39.30
   - Sube: solicitar_codigo_EMAIL.php
   - Renombra sobre solicitar_codigo.php (o mejor, reemplaza directamente)
   ```

2. **Probar**
   ```cmd
   PROBAR_SERVIDOR.bat
   ```

3. **Verificar tu bandeja de entrada**
   - Revisa la carpeta de SPAM si no llega el email

---

## 📋 OPCIÓN 2: Usando PHPMailer con SMTP (RECOMENDADO)

### ✅ Ventajas:
- Emails profesionales que NO van a SPAM
- Funciona con Gmail, Outlook, Yahoo, SendGrid, etc.
- Más confiable

### 📝 Pasos:

### 1️⃣ **Instalar PHPMailer en el servidor**

Conecta por SSH y ejecuta:
```bash
cd /var/www/html
composer require phpmailer/phpmailer
```

### 2️⃣ **Configurar email_config.php**

Edita el archivo `email_config.php` con tus credenciales:

#### 🔵 Para Gmail:
```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'tu_email@gmail.com');
define('SMTP_PASSWORD', 'xxxx xxxx xxxx xxxx'); // Contraseña de aplicación
define('FROM_EMAIL', 'tu_email@gmail.com');
define('FROM_NAME', 'PNKCL IoT');
```

**⚠️ IMPORTANTE para Gmail:**
1. Ve a: https://myaccount.google.com/security
2. Activa "Verificación en 2 pasos"
3. Ve a: https://myaccount.google.com/apppasswords
4. Crea una "Contraseña de aplicación" para "Correo"
5. Usa esa contraseña de 16 caracteres (con espacios) en SMTP_PASSWORD

#### 🔵 Para Outlook/Hotmail:
```php
define('SMTP_HOST', 'smtp-mail.outlook.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'tu_email@outlook.com');
define('SMTP_PASSWORD', 'tu_contraseña');
define('FROM_EMAIL', 'tu_email@outlook.com');
define('FROM_NAME', 'PNKCL IoT');
```

#### 🔵 Para SendGrid (Recomendado para producción):
- 100 emails gratis por día
- Registro: https://sendgrid.com/
```php
define('SMTP_HOST', 'smtp.sendgrid.net');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'apikey');
define('SMTP_PASSWORD', 'TU_API_KEY_DE_SENDGRID');
define('FROM_EMAIL', 'noreply@tudominio.com');
define('FROM_NAME', 'PNKCL IoT');
```

### 3️⃣ **Subir archivos al servidor**

Con WinSCP:
1. Sube `email_config.php`
2. Sube `solicitar_codigo_SMTP.php`
3. Renombra `solicitar_codigo_SMTP.php` a `solicitar_codigo.php`

### 4️⃣ **Probar**
```cmd
PROBAR_SERVIDOR.bat
```

---

## 🧪 ARCHIVO DE PRUEBA

Crea un archivo para probar el envío de emails:

### test_email.php
```php
<?php
require 'vendor/autoload.php';
require 'email_config.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port = SMTP_PORT;
    
    $mail->setFrom(FROM_EMAIL, FROM_NAME);
    $mail->addAddress('tu_email_de_prueba@gmail.com');
    
    $mail->isHTML(true);
    $mail->Subject = 'Prueba de Email - PNKCL IoT';
    $mail->Body = '<h1>¡Funciona!</h1><p>El sistema de emails está configurado correctamente.</p>';
    
    $mail->send();
    echo json_encode(['status' => 'success', 'message' => 'Email enviado correctamente']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
}
?>
```

Pruébalo:
```bash
curl http://98.95.39.30/test_email.php
```

---

## 📱 CAMBIOS EN LA APP (Si es necesario)

La app NO necesita cambios, porque:
- La respuesta JSON sigue siendo la misma
- El código ya no se muestra en el mensaje (solo se envía por email)
- La funcionalidad de validación sigue igual

Pero si quieres mejorar el mensaje en la app, busca donde muestra:
```
"Si el email está registrado, se ha enviado un código..."
```

Y cámbialo a:
```
"Código enviado a tu correo. Revisa tu bandeja de entrada y SPAM."
```

---

## 🔍 SOLUCIÓN DE PROBLEMAS

### ❌ "SMTP connect() failed"
- Verifica que las credenciales sean correctas
- Verifica que el puerto esté abierto en el firewall
- Para Gmail, asegúrate de usar "Contraseña de aplicación"

### ❌ "Authentication failed"
- Credenciales incorrectas
- Para Gmail, necesitas activar "Verificación en 2 pasos" y crear "Contraseña de aplicación"

### ❌ El email llega a SPAM
- Usa un servicio profesional como SendGrid
- Configura SPF y DKIM en tu dominio
- Usa un dominio verificado como remitente

### ❌ No llega ningún email
- Revisa la carpeta de SPAM
- Verifica que el email esté escrito correctamente
- Verifica los logs: `sudo tail -f /var/log/php-fpm/error.log`

---

## 📊 COMPARACIÓN DE OPCIONES

| Característica | mail() | PHPMailer + Gmail | PHPMailer + SendGrid |
|---------------|--------|-------------------|---------------------|
| Facilidad | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐ |
| Confiabilidad | ⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Evita SPAM | ⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Costo | Gratis | Gratis | Gratis (100/día) |
| Configuración | Ninguna | Media | Fácil |

---

## ✅ RECOMENDACIÓN FINAL

**Para desarrollo/pruebas:**
- Usa `solicitar_codigo_EMAIL.php` (mail() simple)
- El código se muestra en el mensaje de respuesta

**Para producción:**
- Usa `solicitar_codigo_SMTP.php` con SendGrid o Gmail
- Emails profesionales y confiables
- Mejor experiencia de usuario

---

## 🎓 SIGUIENTE PASO

1. **Decide qué opción usar**
2. **Sigue los pasos de esa opción**
3. **Prueba con PROBAR_SERVIDOR.bat**
4. **Verifica tu email**
5. **¡Listo!** 🎉


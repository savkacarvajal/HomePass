<?php
?>
*/
define('FROM_NAME', 'HomePass IoT');
define('FROM_EMAIL', 'tu_email@example.com');
define('SMTP_PASSWORD', 'tu_api_key_de_sendgrid');
define('SMTP_USERNAME', 'apikey');
define('SMTP_SECURE', 'tls');
define('SMTP_PORT', 587);
define('SMTP_HOST', 'smtp.sendgrid.net');
Opción 4: SendGrid (Recomendado para producción - 100 emails gratis/día)
/*

*/
define('FROM_NAME', 'HomePass IoT');
define('FROM_EMAIL', 'tu_email@yahoo.com');
define('SMTP_PASSWORD', 'tu_contraseña_app');
define('SMTP_USERNAME', 'tu_email@yahoo.com');
define('SMTP_SECURE', 'tls');
define('SMTP_PORT', 587);
define('SMTP_HOST', 'smtp.mail.yahoo.com');
Opción 3: Yahoo
/*

*/
define('FROM_NAME', 'HomePass IoT');
define('FROM_EMAIL', 'tu_email@outlook.com');
define('SMTP_PASSWORD', 'tu_contraseña');
define('SMTP_USERNAME', 'tu_email@outlook.com');
define('SMTP_SECURE', 'tls');
define('SMTP_PORT', 587);
define('SMTP_HOST', 'smtp-mail.outlook.com');
Opción 2: Outlook/Hotmail
/*

*/
5. Usar esa contraseña de 16 caracteres en SMTP_PASSWORD
4. Crear una "Contraseña de aplicación" para "Correo"
3. Ir a: https://myaccount.google.com/apppasswords
2. Activar "Verificación en 2 pasos"
1. Ir a: https://myaccount.google.com/security
INSTRUCCIONES PARA GMAIL:
/*

define('FROM_NAME', 'HomePass IoT');
define('FROM_EMAIL', 'TU_EMAIL@gmail.com'); // ⚠️ CAMBIAR
define('SMTP_PASSWORD', 'tu_contraseña_app_16_caracteres'); // ⚠️ CAMBIAR
define('SMTP_USERNAME', 'TU_EMAIL@gmail.com'); // ⚠️ CAMBIAR
define('SMTP_SECURE', 'tls'); // 'tls' o 'ssl'
define('SMTP_PORT', 587);
define('SMTP_HOST', 'smtp.gmail.com');
// Opción 1: Gmail (requiere configuración especial)

// ⚠️ IMPORTANTE: Copia este archivo como 'email_config.php' y configura tus credenciales
// email_config.example.php - Plantilla de Configuración de Email


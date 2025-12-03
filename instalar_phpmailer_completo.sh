#!/bin/bash
# Script completo para instalar y configurar PHPMailer

echo "==========================================="
echo "INSTALACIÓN DE PHPMAILER CON SMTP"
echo "==========================================="
echo ""

# Ir al directorio web
cd /var/www/html

echo "📦 Paso 1: Verificando Composer..."
if ! command -v composer &> /dev/null; then
    echo "⏳ Composer no encontrado. Instalando..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    echo "✅ Composer instalado"
else
    echo "✅ Composer ya está instalado"
fi

echo ""
echo "📦 Paso 2: Instalando PHPMailer..."
composer require phpmailer/phpmailer

echo ""
echo "📦 Paso 3: Verificando instalación..."
if [ -d "vendor/phpmailer/phpmailer" ]; then
    echo "✅ PHPMailer instalado correctamente en:"
    echo "   /var/www/html/vendor/phpmailer/phpmailer"
else
    echo "❌ Error: PHPMailer no se instaló correctamente"
    exit 1
fi

echo ""
echo "==========================================="
echo "✅ INSTALACIÓN COMPLETADA"
echo "==========================================="
echo ""
echo "📋 SIGUIENTES PASOS:"
echo ""
echo "1. Edita email_config.php con tus credenciales SMTP"
echo "   sudo nano /var/www/html/email_config.php"
echo ""
echo "2. Configura tu proveedor de email:"
echo "   - Gmail: Necesitas 'Contraseña de aplicación'"
echo "   - Outlook: Usa tu contraseña normal"
echo "   - SendGrid: Regístrate en sendgrid.com (100 emails gratis/día)"
echo ""
echo "3. Sube solicitar_codigo_SMTP.php como solicitar_codigo.php"
echo ""
echo "4. Prueba con:"
echo "   curl -X POST -d 'email=tu_email@gmail.com' http://98.95.39.30/solicitar_codigo.php"
echo ""
echo "==========================================="


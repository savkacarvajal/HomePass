#!/bin/bash
# Script para instalar PHPMailer en el servidor

echo "==========================================="
echo "INSTALANDO PHPMAILER EN EL SERVIDOR"
echo "==========================================="

cd /var/www/html

# Verificar si composer está instalado
if ! command -v composer &> /dev/null
then
    echo "Composer no está instalado. Instalando..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
fi

# Instalar PHPMailer
echo "Instalando PHPMailer..."
composer require phpmailer/phpmailer

echo ""
echo "✅ PHPMailer instalado correctamente"
echo ""
echo "Siguiente paso:"
echo "1. Configurar email_config.php con tus credenciales SMTP"
echo "2. Subir solicitar_codigo_SMTP.php al servidor"
echo "==========================================="


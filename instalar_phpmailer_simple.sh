#!/bin/bash
# Script simple para instalar PHPMailer
# Ejecutar desde /var/www/html

echo "═══════════════════════════════════════════════════════════════"
echo "Instalando PHPMailer..."
echo "═══════════════════════════════════════════════════════════════"
echo ""

cd /var/www/html

# Instalar PHPMailer
/usr/local/bin/composer require phpmailer/phpmailer

# Verificar
if [ -d "vendor/phpmailer/phpmailer" ]; then
    echo ""
    echo "✅ PHPMailer instalado correctamente"
    ls -la vendor/phpmailer/phpmailer/ | head -10
else
    echo ""
    echo "❌ Error al instalar PHPMailer"
fi


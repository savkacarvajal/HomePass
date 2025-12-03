#!/bin/bash
# Script para instalar Composer y PHPMailer automáticamente

echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║  INSTALACIÓN AUTOMÁTICA DE COMPOSER Y PHPMAILER              ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""

# Ir al directorio correcto
cd /var/www/html
echo "✅ Directorio: /var/www/html"
echo ""

# Verificar si composer ya está instalado
if command -v composer &> /dev/null; then
    echo "✅ Composer ya está instalado"
    composer --version
else
    echo "📦 Instalando Composer..."
    echo ""

    # Descargar e instalar Composer
    curl -sS https://getcomposer.org/installer | php

    # Mover composer a un lugar accesible globalmente
    sudo mv composer.phar /usr/local/bin/composer

    # Verificar instalación
    if command -v composer &> /dev/null; then
        echo ""
        echo "✅ Composer instalado correctamente"
        composer --version
    else
        echo ""
        echo "❌ Error al instalar Composer"
        exit 1
    fi
fi

echo ""
echo "═══════════════════════════════════════════════════════════════"
echo "📦 Instalando PHPMailer..."
echo "═══════════════════════════════════════════════════════════════"
echo ""

# Instalar PHPMailer
composer require phpmailer/phpmailer

echo ""
echo "═══════════════════════════════════════════════════════════════"
echo "🔍 Verificando instalación..."
echo "═══════════════════════════════════════════════════════════════"
echo ""

# Verificar que PHPMailer se instaló
if [ -d "vendor/phpmailer/phpmailer" ]; then
    echo "✅ PHPMailer instalado correctamente en:"
    echo "   /var/www/html/vendor/phpmailer/phpmailer/"
    echo ""
    ls -la vendor/phpmailer/phpmailer/ | head -10
    echo ""
    echo "╔═══════════════════════════════════════════════════════════════╗"
    echo "║              ✅ INSTALACIÓN COMPLETADA                        ║"
    echo "╚═══════════════════════════════════════════════════════════════╝"
    echo ""
    echo "Siguientes pasos:"
    echo "  1. Configura email_config.php con tus credenciales"
    echo "  2. Sube archivos con WinSCP"
    echo "  3. Prueba con PROBAR_EMAIL.bat"
    echo ""
else
    echo "❌ Error: PHPMailer no se instaló correctamente"
    echo ""
    echo "Intenta manualmente:"
    echo "  cd /var/www/html"
    echo "  composer require phpmailer/phpmailer"
    exit 1
fi


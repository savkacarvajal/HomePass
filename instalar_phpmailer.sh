#!/bin/bash
# Script para instalar PHPMailer y configurar envío de emails en EC2

echo "📦 INSTALANDO PHPMAILER EN EC2"
echo "================================"
echo ""

# 1. Verificar que estamos en el directorio correcto
cd /var/www/html
echo "✅ Directorio actual: $(pwd)"
echo ""

# 2. Instalar Composer si no está instalado
if ! command -v composer &> /dev/null; then
    echo "📥 Instalando Composer..."
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    echo "✅ Composer instalado"
else
    echo "✅ Composer ya está instalado"
fi
echo ""

# 3. Instalar PHPMailer
echo "📥 Instalando PHPMailer..."
composer require phpmailer/phpmailer
echo ""

# 4. Verificar instalación
if [ -d "vendor/phpmailer/phpmailer" ]; then
    echo "✅ PHPMailer instalado correctamente en:"
    ls -la vendor/phpmailer/phpmailer/
    echo ""
else
    echo "❌ Error: PHPMailer no se instaló correctamente"
    exit 1
fi

# 5. Configurar permisos
echo "🔒 Configurando permisos..."
sudo chown -R apache:apache vendor/
sudo chmod -R 755 vendor/
echo "✅ Permisos configurados"
echo ""

# 6. Verificar archivos PHP subidos
echo "📄 Archivos PHP en /var/www/html:"
ls -lh *.php | head -20
echo ""

echo "✅ INSTALACIÓN COMPLETADA"
echo "========================="
echo ""
echo "Próximos pasos:"
echo "1. Subir solicitar_codigo_con_email.php por WinSCP"
echo "2. Actualizar Constants.kt en la app"
echo "3. Probar envío con test_email.php"


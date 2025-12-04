#!/bin/bash
# Script para configurar permisos después de subir archivos

echo "🔧 Configurando permisos de archivos PHP..."

cd /var/www/html

# Dar permisos a todos los archivos PHP
sudo chown apache:apache *.php
sudo chmod 644 *.php

# Reiniciar Apache
sudo systemctl restart httpd

# Verificar que Apache esté corriendo
if systemctl is-active --quiet httpd; then
    echo "✅ Apache está corriendo correctamente"
else
    echo "❌ Error: Apache no está corriendo"
    sudo systemctl status httpd
fi

# Mostrar archivos PHP en el directorio
echo ""
echo "📁 Archivos PHP en /var/www/html:"
ls -lh *.php

echo ""
echo "✅ Configuración completada"
echo ""
echo "🔍 Ahora prueba:"
echo "   http://44.199.155.199/test_mysql.php"
echo "   http://44.199.155.199/get_users.php"


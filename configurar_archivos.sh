#!/bin/bash
# Script para mover y configurar archivos PHP después de subirlos con WinSCP

echo "=========================================="
echo "CONFIGURANDO ARCHIVOS PHP DE RECUPERACIÓN"
echo "=========================================="
echo ""

# Mover archivos desde /home/ec2-user a /var/www/html
echo "1. Moviendo archivos..."
sudo mv /home/ec2-user/solicitar_codigo_NUEVO.php /var/www/html/solicitar_codigo.php
sudo mv /home/ec2-user/validar_codigo_NUEVO.php /var/www/html/validar_codigo.php

# Establecer permisos correctos
echo "2. Estableciendo permisos 644..."
sudo chmod 644 /var/www/html/solicitar_codigo.php
sudo chmod 644 /var/www/html/validar_codigo.php

# Establecer propietario correcto
echo "3. Estableciendo propietario apache:apache..."
sudo chown apache:apache /var/www/html/solicitar_codigo.php
sudo chown apache:apache /var/www/html/validar_codigo.php

# Verificar archivos
echo ""
echo "4. Verificando archivos..."
ls -lh /var/www/html/ | grep -E "(solicitar|validar)_codigo"

# Mostrar primeras líneas
echo ""
echo "5. Contenido de solicitar_codigo.php (primeras 10 líneas):"
head -10 /var/www/html/solicitar_codigo.php

echo ""
echo "=========================================="
echo "✅ ARCHIVOS CONFIGURADOS CORRECTAMENTE"
echo "=========================================="
echo ""
echo "Ahora prueba desde tu PC con:"
echo "curl -v -X POST -d \"email=test@example.com\" http://98.95.39.30/solicitar_codigo.php"
echo ""


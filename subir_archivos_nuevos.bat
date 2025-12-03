@echo off
echo ========================================
echo Subiendo archivos PHP NUEVOS al servidor
echo ========================================
echo.

REM Reemplazar con tu clave privada y configuración
set SERVER_USER=ec2-user
set SERVER_IP=98.95.39.30
set SERVER_PATH=/var/www/html/
set KEY_PATH=C:\Users\savka\.ssh\your-key.pem

echo Subiendo solicitar_codigo_NUEVO.php...
scp -i "%KEY_PATH%" solicitar_codigo_NUEVO.php %SERVER_USER%@%SERVER_IP%:%SERVER_PATH%solicitar_codigo.php

echo.
echo Subiendo validar_codigo_NUEVO.php...
scp -i "%KEY_PATH%" validar_codigo_NUEVO.php %SERVER_USER%@%SERVER_IP%:%SERVER_PATH%validar_codigo.php

echo.
echo Estableciendo permisos...
ssh -i "%KEY_PATH%" %SERVER_USER%@%SERVER_IP% "sudo chmod 644 %SERVER_PATH%solicitar_codigo.php %SERVER_PATH%validar_codigo.php"

echo.
echo ========================================
echo SUBIDA COMPLETADA
echo ========================================
echo.
echo IMPORTANTE: Verifica que la tabla password_resets exista en tu BD
echo Ejecuta el archivo crear_tabla_codigos.sql si no existe
echo.
pause


@echo off
chcp 65001 >nul
color 0E
echo ==========================================
echo    PROBAR ENVÍO DE EMAIL
echo ==========================================
echo.
echo Ingresa el email donde quieres recibir la prueba:
set /p test_email=Email:
echo.
echo ==========================================
echo Enviando código de recuperación a: %test_email%
echo ==========================================
echo.

curl -s -X POST -d "email=%test_email%" http://98.95.39.30/solicitar_codigo.php

echo.
echo.
echo ==========================================
echo REVISA TU EMAIL:
echo ==========================================
echo.
echo 1. Bandeja de entrada
echo 2. Carpeta de SPAM
echo 3. Carpeta de Promociones (Gmail)
echo.
echo Si no llega el email:
echo   - Verifica que el email esté correcto
echo   - Revisa la configuración en email_config.php
echo   - Ejecuta: ssh ec2-user@98.95.39.30 "sudo tail -f /var/log/php-fpm/error.log"
echo.
echo ==========================================
echo.
pause


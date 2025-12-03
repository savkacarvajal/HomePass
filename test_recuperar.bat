@echo off
echo ========================================
echo PRUEBA DE RECUPERACIÓN DE CONTRASEÑA
echo ========================================
echo.

echo 1. Probando test_conexion.php...
curl -v http://98.95.39.30/test_conexion.php
echo.
echo.

echo 2. Solicitando código para test@example.com...
curl -v -X POST -d "email=test@example.com" http://98.95.39.30/solicitar_codigo.php
echo.
echo.

echo ========================================
echo PRUEBA COMPLETADA
echo ========================================
echo.
echo Si ves Content-Length: 0, el PHP tiene un problema
echo Si ves un JSON con status y message, funciona correctamente
echo.
pause


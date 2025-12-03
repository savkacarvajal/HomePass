@echo off
echo ========================================
echo PROBANDO ARCHIVOS PHP EN EL SERVIDOR
echo ========================================
echo.

echo 1. Probando test_conexion.php...
curl -v http://98.95.39.30/test_conexion.php
echo.
echo.

echo ========================================
echo 2. Probando solicitar_codigo.php...
curl -v -X POST -d "email=luna@gmail.com" http://98.95.39.30/solicitar_codigo.php
echo.
echo.

echo ========================================
echo ANALISIS DE RESPUESTAS:
echo ========================================
echo.
echo Si ves JSON con "status" y "message" - FUNCIONA correctamente
echo Si ves "Content-Length: 0" - Los archivos estan vacios o tienen errores
echo.
pause


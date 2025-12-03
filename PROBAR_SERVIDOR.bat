@echo off
REM Este script prueba los archivos PHP directamente desde Windows
REM Ejecutar desde cmd.exe (NO PowerShell)

echo ========================================
echo PROBANDO ARCHIVOS PHP EN EL SERVIDOR
echo ========================================
echo.

echo 1. Probando test_conexion.php...
echo.
curl -s http://98.95.39.30/test_conexion.php
echo.
echo.

echo ========================================
echo 2. Probando solicitar_codigo.php con email luna@gmail.com...
echo.
curl -s -X POST -d "email=luna@gmail.com" http://98.95.39.30/solicitar_codigo.php
echo.
echo.

echo ========================================
echo 3. Probando validar_codigo.php (solo prueba de estructura)...
echo.
curl -s -X POST -d "email=luna@gmail.com" -d "code=00000" http://98.95.39.30/validar_codigo.php
echo.
echo.

echo ========================================
echo ANALISIS:
echo ========================================
echo.
echo [OK] Si ves JSON con "status" y "message" = FUNCIONA
echo [ERROR] Si NO ves nada o ves HTML = HAY UN PROBLEMA
echo.
echo Presiona cualquier tecla para cerrar...
pause >nul


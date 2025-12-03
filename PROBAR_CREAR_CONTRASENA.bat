@echo off
echo ========================================
echo PROBANDO apimodificarclave.php
echo ========================================
echo.

echo Probando actualización de contraseña para luna@gmail.com...
echo.
curl -s -X POST -d "email=luna@gmail.com" -d "new_password=Test1234!" http://98.95.39.30/apimodificarclave.php
echo.
echo.

echo ========================================
echo ANALISIS:
echo ========================================
echo.
echo [OK] Si ves {"status":"success",...} = FUNCIONA
echo [ERROR] Si ves {"status":"error",...} o vacio = HAY PROBLEMA
echo.
echo Despues de subir el archivo con WinSCP, ejecuta este script de nuevo.
echo.
pause


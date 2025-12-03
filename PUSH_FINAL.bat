@echo off
chcp 65001 > nul
echo.
echo ========================================
echo   PUSH FINAL A GITHUB
echo ========================================
echo.

cd /d "%~dp0"

echo Verificando estado...
git status

echo.
echo ========================================
echo IMPORTANTE: Necesitas tus credenciales
echo Usuario: savkacarvajal
echo Token: Generalo en https://github.com/settings/tokens
echo ========================================
echo.
pause

echo Haciendo push forzado a GitHub...
git push -u origin main --force

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo   EXITO! Codigo subido a GitHub
    echo ========================================
    echo.
    echo Verifica tu repositorio en:
    echo https://github.com/savkacarvajal/HomePass
    echo.
) else (
    echo.
    echo ========================================
    echo   ERROR al hacer push
    echo ========================================
    echo.
    echo Posibles soluciones:
    echo 1. Verifica tu usuario y token
    echo 2. Asegurate de tener conexion a internet
    echo 3. Genera un nuevo token con permisos 'repo'
    echo.
)

pause


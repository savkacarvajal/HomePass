@echo off
chcp 65001 > nul
echo ========================================
echo   LIMPIAR HISTORIAL Y CREAR NUEVO REPO
echo ========================================
echo.

cd /d "%~dp0"

echo [1/8] Respaldando la carpeta .git actual...
if exist ".git.backup" rmdir /s /q ".git.backup"
move ".git" ".git.backup"
echo Respaldo creado: .git.backup
echo.

echo [2/8] Inicializando repositorio Git limpio...
git init
echo.

echo [3/8] Configurando usuario...
git config user.name "savkacarvajal"
git config user.email "savka.carvajal@inacapmail.cl"
echo Usuario configurado
echo.

echo [4/8] Añadiendo remoto de GitHub...
git remote add origin https://github.com/savkacarvajal/HomePass.git
echo Remoto añadido
echo.

echo [5/8] Creando rama main...
git checkout -b main
echo.

echo [6/8] Añadiendo todos los archivos...
git add .
echo.

echo [7/8] Creando commit inicial limpio...
git commit -m "Initial commit: HomePass - App de gestion de contraseñas con servidor AWS 44.199.155.199"
echo.

echo [8/8] Subiendo a GitHub con push forzado...
echo.
echo IMPORTANTE: Se te pedira tu usuario y token de GitHub
echo Usuario: savkacarvajal
echo Token: (genera uno nuevo en https://github.com/settings/tokens)
echo.
pause

git push -u origin main --force

echo.
echo ========================================
echo   PROCESO COMPLETADO
echo ========================================
echo.
echo Verifica tu repositorio en:
echo https://github.com/savkacarvajal/HomePass
echo.
echo El historial ahora esta completamente limpio.
echo La carpeta .git.backup contiene el respaldo del repo anterior.
echo.
pause


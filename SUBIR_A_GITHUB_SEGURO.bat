@echo off
chcp 65001 > nul
echo ========================================
echo   SUBIR PROYECTO A GITHUB (SEGURO)
echo ========================================
echo.

cd /d "%~dp0"

echo [1/5] Verificando estado de Git...
git status
echo.

echo [2/5] Añadiendo archivos nuevos...
git add CONFIGURACION_SEGURA.md
git add PLAN_MEJORAS_HOMEPASS.md
echo.

echo [3/5] Creando commit...
git commit -m "Agregar documentacion de mejoras y configuracion segura"
echo.

echo [4/5] Verificando remoto...
git remote -v
echo.

echo [5/5] Subiendo a GitHub...
echo.
echo IMPORTANTE: Se te pedira tu usuario y token de GitHub
echo Usuario: savkacarvajal
echo Token: (el nuevo token que debes generar)
echo.
pause

git push -u origin main

echo.
echo ========================================
echo   PROCESO COMPLETADO
echo ========================================
echo.
echo Si hubo algun error:
echo 1. Verifica que el token sea correcto
echo 2. Genera un nuevo token en: https://github.com/settings/tokens
echo 3. Asegurate de tener permisos de escritura
echo.
pause


@echo off
chcp 65001 > nul
echo ========================================
echo   SINCRONIZAR Y SUBIR A GITHUB
echo ========================================
echo.

cd /d "%~dp0"

echo [1/6] Estado actual de Git...
git status
echo.

echo [2/6] Obteniendo cambios del repositorio remoto...
git fetch origin
echo.

echo [3/6] Integrando cambios remotos con rebase...
echo (Esto mantiene el historial limpio)
git pull origin main --rebase --allow-unrelated-histories
echo.

echo Si hubo conflictos, se te mostraran arriba.
echo Si NO hay conflictos, continuamos automaticamente...
echo.
pause

echo [4/6] Verificando estado despues de la sincronizacion...
git status
echo.

echo [5/6] Añadiendo todos los archivos...
git add .
echo.

echo [6/6] Subiendo a GitHub...
echo.
echo IMPORTANTE: Se te pedira tu usuario y token de GitHub
echo Usuario: savkacarvajal
echo Token: (el token que generaste en GitHub)
echo.

git push -u origin main

echo.
echo ========================================
echo   PROCESO COMPLETADO
echo ========================================
echo.
echo Verifica tu repositorio en:
echo https://github.com/savkacarvajal/HomePass
echo.
pause


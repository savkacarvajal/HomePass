@echo off
chcp 65001 > nul
echo ========================================
echo   LIMPIAR CREDENCIALES Y CONFIGURAR GIT
echo ========================================
echo.

cd /d "%~dp0"

echo [1/4] Limpiando credenciales antiguas de Windows...
cmdkey /list | findstr "github.com"
echo.
echo Si ves credenciales de GitHub arriba, las vamos a eliminar...
pause

cmdkey /delete:LegacyGeneric:target=git:https://github.com
cmdkey /delete:LegacyGeneric:target=git:https://github.com/

echo.
echo [2/4] Verificando usuario de Git local...
git config user.name
git config user.email
echo.

echo [3/4] Configurando usuario correcto...
git config user.name "savkacarvajal"
git config user.email "savka.carvajal@inacapmail.cl"
echo Usuario configurado: savkacarvajal
echo.

echo [4/4] Verificando repositorio remoto...
git remote -v
echo.

echo ========================================
echo   CREDENCIALES LIMPIADAS
echo ========================================
echo.
echo SIGUIENTE PASO:
echo 1. Verifica que el repositorio existe en: https://github.com/savkacarvajal/HomePass
echo 2. Si NO existe, necesitas crearlo primero en GitHub
echo 3. Ejecuta de nuevo: SUBIR_A_GITHUB_SEGURO.bat
echo 4. Usa tus credenciales de savkacarvajal (no de PandaAkiraNakai)
echo.
pause


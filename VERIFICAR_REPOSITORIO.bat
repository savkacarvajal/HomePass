@echo off
chcp 65001 > nul
echo ========================================
echo   VERIFICAR REPOSITORIO EN GITHUB
echo ========================================
echo.

echo Abriendo tu perfil de GitHub...
start https://github.com/savkacarvajal

echo.
echo INSTRUCCIONES:
echo.
echo 1. Verifica si el repositorio "HomePass" existe
echo 2. Si NO existe, necesitas crearlo:
echo.
echo    a) Clic en el boton verde "New" (arriba a la derecha)
echo    b) Repository name: HomePass
echo    c) Description: App de gestion de contraseñas para hogar inteligente
echo    d) Public o Private (tu eliges)
echo    e) NO marques "Add a README file"
echo    f) NO marques "Add .gitignore"
echo    g) NO marques "Choose a license"
echo    h) Clic en "Create repository"
echo.
echo 3. Una vez creado, vuelve aqui y presiona una tecla
echo.
pause

echo.
echo ========================================
echo   SIGUIENTE PASO
echo ========================================
echo.
echo Si el repositorio ya existe o lo acabas de crear:
echo 1. Ejecuta: LIMPIAR_CREDENCIALES.bat
echo 2. Luego ejecuta: SUBIR_A_GITHUB_SEGURO.bat
echo.
pause


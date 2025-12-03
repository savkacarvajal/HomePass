@echo off
echo ========================================
echo CONFIGURAR Y SUBIR A GITHUB - HomePass
echo ========================================
echo.

echo [PASO 1] Configurar Git (solo primera vez)
echo.
set /p git_name="Ingresa tu nombre para Git (ejemplo: Juan Perez): "
set /p git_email="Ingresa tu email de GitHub: "

git config --global user.name "%git_name%"
git config --global user.email "%git_email%"

echo.
echo ✓ Git configurado correctamente
echo   Nombre: %git_name%
echo   Email: %git_email%
echo.

echo [PASO 2] Verificar configuracion
git config --global user.name
git config --global user.email
echo.

echo ========================================
echo AHORA VE A GITHUB Y CREA EL REPOSITORIO
echo ========================================
echo.
echo 1. Ve a: https://github.com/new
echo 2. Nombre del repositorio: HomePasss
echo 3. Descripcion: Aplicacion Android de gestion de usuarios
echo 4. Visibilidad: Public o Private
echo 5. NO marques ninguna opcion adicional
echo 6. Haz clic en "Create repository"
echo.

pause

echo.
echo [PASO 3] Conectar con el repositorio de GitHub
echo.
set /p github_user="Ingresa tu usuario de GitHub: "
set github_url=https://github.com/%github_user%/HomePass.git

echo.
echo Conectando con: %github_url%
echo.

cd "C:\Users\savka\AndroidStudioProjects\HomePass 1.0"

git remote remove origin 2>nul
git remote add origin %github_url%

echo.
echo [PASO 4] Verificar conexion
git remote -v
echo.

echo [PASO 5] Cambiar a rama main
git branch -M main
echo.

echo [PASO 6] Subir el codigo a GitHub
echo.
echo ⚠️ Se te pedira autenticacion de GitHub
echo.
git push -u origin main

echo.
echo ========================================
echo ✓ PROCESO COMPLETADO
echo ========================================
echo.
echo Tu codigo esta ahora en:
echo https://github.com/%github_user%/HomePass
echo.

pause


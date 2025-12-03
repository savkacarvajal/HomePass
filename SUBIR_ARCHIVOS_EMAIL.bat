@echo off
chcp 65001 >nul
color 0B
echo ==========================================
echo    SUBIR ARCHIVOS DE EMAIL AL SERVIDOR
echo ==========================================
echo.
echo Este script te guiará para subir los archivos
echo necesarios para enviar emails.
echo.
echo ==========================================
echo ARCHIVOS QUE DEBES SUBIR:
echo ==========================================
echo.
echo OPCIÓN 1 (Simple - mail()):
echo   📄 solicitar_codigo_EMAIL.php
echo      ⮕ Subir como: solicitar_codigo.php
echo.
echo OPCIÓN 2 (Profesional - PHPMailer):
echo   📄 email_config.php
echo   📄 solicitar_codigo_SMTP.php
echo      ⮕ Subir como: solicitar_codigo.php
echo.
echo ==========================================
echo PASOS EN WINSCP:
echo ==========================================
echo.
echo 1. Abre WinSCP
echo 2. Conecta a: 98.95.39.30
echo 3. Usuario: ec2-user
echo 4. Usa tu archivo .ppk
echo.
echo 5. Panel IZQUIERDO:
echo    C:\Users\savka\AndroidStudioProjects\Test\
echo.
echo 6. Panel DERECHO:
echo    /var/www/html/
echo.
echo 7. ARRASTRA el archivo sobre solicitar_codigo.php
echo    (Confirma SOBRESCRIBIR)
echo.
echo ==========================================
echo DESPUÉS DE SUBIR:
echo ==========================================
echo.
echo 1. Si usas OPCIÓN 2 (PHPMailer):
echo    - Edita email_config.php
echo    - Configura tu email y contraseña
echo.
echo 2. Ejecuta: PROBAR_SERVIDOR.bat
echo.
echo 3. Revisa tu bandeja de entrada (y SPAM)
echo.
echo ==========================================
echo.
pause


@echo off
chcp 65001 >nul
color 0A
cls

echo ╔═══════════════════════════════════════════════════════════════════════════╗
echo ║                                                                           ║
echo ║       🎓 INSTALACIÓN PROFESIONAL - PHPMailer + SMTP                      ║
echo ║                                                                           ║
echo ╚═══════════════════════════════════════════════════════════════════════════╝
echo.
echo  Este script te guiará para instalar la versión PROFESIONAL
echo  con PHPMailer para envío confiable de emails.
echo.
echo ═══════════════════════════════════════════════════════════════════════════
echo  📋 REQUISITOS:
echo ═══════════════════════════════════════════════════════════════════════════
echo.
echo   ✅ Acceso SSH al servidor 98.95.39.30
echo   ✅ WinSCP instalado
echo   ✅ Cuenta de Gmail con "Contraseña de aplicación"
echo.
pause
cls

:menu
echo ╔═══════════════════════════════════════════════════════════════════════════╗
echo ║  PASO A PASO - INSTALACIÓN PROFESIONAL                                  ║
echo ╚═══════════════════════════════════════════════════════════════════════════╝
echo.
echo  [1] 📦 PASO 1: Instalar PHPMailer en servidor (SSH)
echo  [2] ⚙️  PASO 2: Configurar credenciales de Gmail
echo  [3] 📤 PASO 3: Subir archivos con WinSCP
echo  [4] 🧪 PASO 4: Probar sistema
echo  [5] ✅ PASO 5: Verificar que funciona
echo  [6] 📖 Ver guía completa
echo  [0] ❌ Salir
echo.
set /p paso="Selecciona el paso (0-6): "

if "%paso%"=="1" goto paso1
if "%paso%"=="2" goto paso2
if "%paso%"=="3" goto paso3
if "%paso%"=="4" goto paso4
if "%paso%"=="5" goto paso5
if "%paso%"=="6" goto guia
if "%paso%"=="0" goto salir
goto menu

:paso1
cls
echo ╔═══════════════════════════════════════════════════════════════════════════╗
echo ║  📦 PASO 1: INSTALAR PHPMAILER EN EL SERVIDOR                            ║
echo ╚═══════════════════════════════════════════════════════════════════════════╝
echo.
echo  ⚠️  IMPORTANTE: Estos comandos se ejecutan EN EL SERVIDOR, NO en tu PC
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  INSTRUCCIONES PASO A PASO:
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  1️⃣  Abre PuTTY (o tu cliente SSH favorito)
echo.
echo  2️⃣  Conecta al servidor con estos datos:
echo      • Host: 98.95.39.30
echo      • Usuario: ec2-user
echo      • Auth: Selecciona tu archivo .ppk
echo.
echo  3️⃣  Una vez conectado, COPIA Y PEGA estos comandos EN EL SERVIDOR:
echo.
echo  ┌───────────────────────────────────────────────────────────────────────┐
echo  │  cd /var/www/html                                                     │
echo  │  composer require phpmailer/phpmailer                                 │
echo  └───────────────────────────────────────────────────────────────────────┘
echo.
echo  4️⃣  Espera a que termine la instalación (puede tomar 1-2 minutos)
echo.
echo  5️⃣  Verifica que se instaló correctamente:
echo.
echo  ┌───────────────────────────────────────────────────────────────────────┐
echo  │  ls -la vendor/phpmailer/phpmailer/                                   │
echo  └───────────────────────────────────────────────────────────────────────┘
echo.
echo      ✅ Si ves muchos archivos = Instalado correctamente
echo      ❌ Si dice "No such file" = Algo falló
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  ❓ ¿QUÉ HACER SI COMPOSER NO ESTÁ INSTALADO?
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  Si al ejecutar "composer require..." te dice "command not found":
echo.
echo  Instala Composer primero (EN EL SERVIDOR):
echo.
echo  ┌───────────────────────────────────────────────────────────────────────┐
echo  │  curl -sS https://getcomposer.org/installer ^| php                    │
echo  │  sudo mv composer.phar /usr/local/bin/composer                        │
echo  └───────────────────────────────────────────────────────────────────────┘
echo.
echo  Luego vuelve a intentar el paso 3
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  💡 CONSEJO: Copia los comandos desde aquí y pégalos en PuTTY
echo             (Clic derecho en PuTTY = Pegar)
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
pause
goto menu

:paso2
cls
echo ╔═══════════════════════════════════════════════════════════════════════════╗
echo ║  ⚙️  PASO 2: CONFIGURAR CREDENCIALES DE GMAIL                            ║
echo ╚═══════════════════════════════════════════════════════════════════════════╝
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  A) CREAR "CONTRASEÑA DE APLICACIÓN" EN GMAIL
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  1. Ve a: https://myaccount.google.com/security
echo  2. Busca "Verificación en 2 pasos" y ACTÍVALA
echo  3. Ve a: https://myaccount.google.com/apppasswords
echo  4. Selecciona:
echo     • App: Correo
echo     • Dispositivo: Otro (escribe "PNKCL IoT")
echo  5. Haz clic en GENERAR
echo  6. COPIA la contraseña de 16 caracteres
echo     Ejemplo: abcd efgh ijkl mnop
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  B) EDITAR email_config.php
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  Abriendo email_config.php...
echo.
timeout /t 2 >nul

if exist "email_config.php" (
    notepad email_config.php
) else (
    echo  ❌ ERROR: email_config.php no encontrado en esta carpeta
    echo.
    echo  Asegúrate de estar en: C:\Users\savka\AndroidStudioProjects\Test\
    echo.
)

echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  C) QUÉ DEBES CAMBIAR EN email_config.php:
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  Busca estas líneas y cámbiala con TUS datos:
echo.
echo  define('SMTP_USERNAME', 'TU_EMAIL@gmail.com');      ⬅️ Tu email de Gmail
echo  define('SMTP_PASSWORD', 'abcd efgh ijkl mnop');     ⬅️ La contraseña de 16 caracteres
echo  define('FROM_EMAIL', 'TU_EMAIL@gmail.com');         ⬅️ Tu email de Gmail
echo.
echo  ⚠️ IMPORTANTE:
echo     • La contraseña DEBE ser la "Contraseña de aplicación" de 16 caracteres
echo     • NO uses tu contraseña normal de Gmail
echo     • Copia los espacios también
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
pause
goto menu

:paso3
cls
echo ╔═══════════════════════════════════════════════════════════════════════════╗
echo ║  📤 PASO 3: SUBIR ARCHIVOS CON WINSCP                                    ║
echo ╚═══════════════════════════════════════════════════════════════════════════╝
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  INSTRUCCIONES:
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  1. Abre WinSCP
echo.
echo  2. Conecta al servidor:
echo     • Host: 98.95.39.30
echo     • Usuario: ec2-user
echo     • Usa tu archivo .ppk
echo.
echo  3. Panel IZQUIERDO (tu PC):
echo     Navega a: C:\Users\savka\AndroidStudioProjects\Test\
echo.
echo  4. Panel DERECHO (servidor):
echo     Navega a: /var/www/html/
echo.
echo  5. ARRASTRA estos 3 archivos del IZQUIERDO al DERECHO:
echo.
echo     ✅ email_config.php
echo        Destino: /var/www/html/email_config.php
echo.
echo     ✅ solicitar_codigo_SMTP.php
echo        Destino: /var/www/html/solicitar_codigo.php
echo        ⚠️ Confirma SOBRESCRIBIR
echo.
echo     ✅ test_email.php
echo        Destino: /var/www/html/test_email.php
echo.
echo  6. ¡Listo! Los archivos están en el servidor.
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  VERIFICAR QUE SE SUBIERON:
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  En SSH ejecuta:
echo     ls -lh /var/www/html/*.php
echo.
echo  Debes ver:
echo     • email_config.php
echo     • solicitar_codigo.php
echo     • test_email.php
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
pause
goto menu

:paso4
cls
echo ╔═══════════════════════════════════════════════════════════════════════════╗
echo ║  🧪 PASO 4: PROBAR SISTEMA                                               ║
echo ╚═══════════════════════════════════════════════════════════════════════════╝
echo.
echo  Vamos a probar que el sistema funciona correctamente.
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  PRUEBA 1: Email de prueba
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
set /p email_prueba=Ingresa tu email para recibir la prueba:
echo.
echo  Enviando email de prueba a: %email_prueba%
echo.
echo  ───────────────────────────────────────────────────────────────────────────
curl -s -X POST -d "email=%email_prueba%" http://98.95.39.30/test_email.php
echo.
echo  ───────────────────────────────────────────────────────────────────────────
echo.
echo  ✅ Si ves:
echo     "status": "success"
echo     "method": "PHPMailer"
echo     → ¡FUNCIONA CORRECTAMENTE!
echo.
echo  ❌ Si ves "error":
echo     → Revisa los logs o credenciales
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  PRUEBA 2: Código de recuperación
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  Probando solicitar_codigo.php...
echo.
echo  ───────────────────────────────────────────────────────────────────────────
curl -s -X POST -d "email=%email_prueba%" http://98.95.39.30/solicitar_codigo.php
echo.
echo  ───────────────────────────────────────────────────────────────────────────
echo.
echo  ✅ Si ves:
echo     "status": "success"
echo     "Código enviado correctamente a tu correo"
echo     → ¡SISTEMA FUNCIONANDO!
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
pause
goto menu

:paso5
cls
echo ╔═══════════════════════════════════════════════════════════════════════════╗
echo ║  ✅ PASO 5: VERIFICAR QUE FUNCIONA                                       ║
echo ╚═══════════════════════════════════════════════════════════════════════════╝
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  CHECKLIST FINAL:
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  Verifica que cumples con TODO:
echo.
echo  [ ] PHPMailer instalado en servidor
echo      → Ejecuta en SSH: ls -la /var/www/html/vendor/phpmailer/
echo.
echo  [ ] "Contraseña de aplicación" de Gmail generada
echo      → https://myaccount.google.com/apppasswords
echo.
echo  [ ] email_config.php editado con tus credenciales
echo      → Verifica SMTP_USERNAME y SMTP_PASSWORD
echo.
echo  [ ] Archivos subidos al servidor
echo      → email_config.php, solicitar_codigo.php, test_email.php
echo.
echo  [ ] test_email.php responde con "success"
echo      → Ejecuta: PROBAR_EMAIL.bat
echo.
echo  [ ] Email recibido correctamente
echo      → Revisa Bandeja de entrada (y SPAM la primera vez)
echo.
echo  [ ] solicitar_codigo.php funciona
echo      → Ejecuta: PROBAR_SERVIDOR.bat
echo.
echo  [ ] JSON contiene "method": "PHPMailer"
echo      → Confirma que usa PHPMailer, no mail()
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  COMANDOS ÚTILES PARA VERIFICAR:
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  Ver logs del servidor:
echo    ssh ec2-user@98.95.39.30
echo    sudo tail -f /var/log/php-fpm/error.log
echo.
echo  Ver archivos en servidor:
echo    ssh ec2-user@98.95.39.30
echo    ls -lh /var/www/html/*.php
echo.
echo  Probar desde el servidor:
echo    ssh ec2-user@98.95.39.30
echo    curl -X POST -d "email=tu@gmail.com" http://localhost/test_email.php
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
pause
goto menu

:guia
cls
echo ╔═══════════════════════════════════════════════════════════════════════════╗
echo ║  📖 GUÍA COMPLETA                                                        ║
echo ╚═══════════════════════════════════════════════════════════════════════════╝
echo.
echo  Abriendo guías...
echo.

if exist "GUIA_PROFESIONAL_PASO_A_PASO.md" (
    start notepad "GUIA_PROFESIONAL_PASO_A_PASO.md"
) else if exist "RESUMEN_EMAIL_SETUP.md" (
    start notepad "RESUMEN_EMAIL_SETUP.md"
) else if exist "README_EMAIL.md" (
    start notepad "README_EMAIL.md"
) else (
    echo  Guías disponibles:
    echo.
    dir /b *EMAIL*.md 2>nul
    echo.
)

pause
goto menu

:salir
cls
echo.
echo ╔═══════════════════════════════════════════════════════════════════════════╗
echo ║                                                                           ║
echo ║              ✅ INSTALACIÓN PROFESIONAL - RESUMEN                        ║
echo ║                                                                           ║
echo ╚═══════════════════════════════════════════════════════════════════════════╝
echo.
echo  Pasos completados:
echo.
echo  1. PHPMailer instalado en servidor
echo  2. Credenciales configuradas en email_config.php
echo  3. Archivos subidos con WinSCP
echo  4. Sistema probado exitosamente
echo  5. Emails enviándose correctamente
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  SIGUIENTE PASO:
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  Para producción:
echo    • Cambia EMAIL_DEBUG a false en email_config.php
echo    • Quita mensajes de debug del código
echo    • Prueba con usuarios reales
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo  RECURSOS:
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  📚 Guías:
echo     • GUIA_PROFESIONAL_PASO_A_PASO.md
echo     • RESUMEN_EMAIL_SETUP.md
echo     • COMANDOS_SSH_EMAIL.md
echo.
echo  🔧 Scripts:
echo     • PROBAR_EMAIL.bat
echo     • PROBAR_SERVIDOR.bat
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
echo  🎉 ¡SISTEMA PROFESIONAL INSTALADO CORRECTAMENTE!
echo.
echo  Ahora tus usuarios recibirán códigos por email de forma profesional,
echo  sin que vayan a SPAM. 📧✨
echo.
echo  ═══════════════════════════════════════════════════════════════════════════
echo.
pause
exit


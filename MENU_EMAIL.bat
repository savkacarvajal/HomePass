@echo off
chcp 65001 >nul
color 0B

:menu
cls
echo ╔════════════════════════════════════════════════════════╗
echo ║   📧 CONFIGURACIÓN DE ENVÍO DE EMAILS - MENU          ║
echo ╚════════════════════════════════════════════════════════╝
echo.
echo  Archivos creados para ti:
echo  ══════════════════════════════════════════════════════
echo.
echo  📄 PHP (Para subir al servidor):
echo     • solicitar_codigo_EMAIL.php (Versión simple)
echo     • solicitar_codigo_SMTP.php (Versión profesional)
echo     • email_config.php (Configuración)
echo     • test_email.php (Pruebas)
echo.
echo  📚 Guías y documentación:
echo     • RESUMEN_EMAIL_SETUP.md (⭐ EMPIEZA AQUÍ)
echo     • GUIA_CONFIGURAR_EMAIL.md (Guía detallada)
echo     • COMANDOS_SSH_EMAIL.md (Comandos listos)
echo.
echo  🔧 Scripts de ayuda:
echo     • SUBIR_ARCHIVOS_EMAIL.bat
echo     • PROBAR_EMAIL.bat
echo     • instalar_phpmailer_completo.sh
echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║  ¿QUÉ QUIERES HACER?                                  ║
echo ╚════════════════════════════════════════════════════════╝
echo.
echo  [1] 📖 Ver resumen rápido (RECOMENDADO PARA EMPEZAR)
echo  [2] 📚 Ver guía detallada
echo  [3] 💻 Ver comandos SSH
echo  [4] 📤 Guía para subir archivos con WinSCP
echo  [5] 🧪 Probar envío de email
echo  [6] 🔍 Verificar archivos locales
echo  [7] 🌐 Probar servidor
echo  [8] ❌ Salir
echo.
set /p opcion="Elige una opción (1-8): "

if "%opcion%"=="1" goto resumen
if "%opcion%"=="2" goto guia
if "%opcion%"=="3" goto comandos
if "%opcion%"=="4" goto winscp
if "%opcion%"=="5" goto probar_email
if "%opcion%"=="6" goto verificar
if "%opcion%"=="7" goto probar_servidor
if "%opcion%"=="8" goto salir
goto menu

:resumen
cls
echo ╔════════════════════════════════════════════════════════╗
echo ║  📖 RESUMEN RÁPIDO                                    ║
echo ╚════════════════════════════════════════════════════════╝
echo.
type RESUMEN_EMAIL_SETUP.md 2>nul || echo Error: RESUMEN_EMAIL_SETUP.md no encontrado
echo.
pause
goto menu

:guia
cls
echo ╔════════════════════════════════════════════════════════╗
echo ║  📚 GUÍA DETALLADA                                    ║
echo ╚════════════════════════════════════════════════════════╝
echo.
type GUIA_CONFIGURAR_EMAIL.md 2>nul || echo Error: GUIA_CONFIGURAR_EMAIL.md no encontrado
echo.
pause
goto menu

:comandos
cls
echo ╔════════════════════════════════════════════════════════╗
echo ║  💻 COMANDOS SSH                                      ║
echo ╚════════════════════════════════════════════════════════╝
echo.
type COMANDOS_SSH_EMAIL.md 2>nul || echo Error: COMANDOS_SSH_EMAIL.md no encontrado
echo.
pause
goto menu

:winscp
cls
echo ╔════════════════════════════════════════════════════════╗
echo ║  📤 GUÍA WINSCP                                       ║
echo ╚════════════════════════════════════════════════════════╝
echo.
echo  OPCIÓN 1: SIMPLE (mail())
echo  ─────────────────────────────
echo  1. Abre WinSCP
echo  2. Conecta a: 98.95.39.30
echo  3. Usuario: ec2-user
echo  4. Usa tu archivo .ppk
echo  5. Panel derecho: /var/www/html/
echo  6. Arrastra: solicitar_codigo_EMAIL.php
echo     Sobre: solicitar_codigo.php
echo  7. Confirma SOBRESCRIBIR
echo  8. ¡Listo! Ejecuta PROBAR_SERVIDOR.bat
echo.
echo  OPCIÓN 2: PROFESIONAL (PHPMailer)
echo  ─────────────────────────────────
echo  1. Primero, instala PHPMailer en el servidor:
echo     ssh ec2-user@98.95.39.30
echo     cd /var/www/html
echo     composer require phpmailer/phpmailer
echo.
echo  2. Edita email_config.php con tus credenciales
echo     (Gmail, Outlook, SendGrid, etc.)
echo.
echo  3. Sube con WinSCP:
echo     • email_config.php → /var/www/html/
echo     • solicitar_codigo_SMTP.php → /var/www/html/solicitar_codigo.php
echo     • test_email.php → /var/www/html/
echo.
echo  4. Prueba: PROBAR_EMAIL.bat
echo.
pause
goto menu

:probar_email
cls
echo ╔════════════════════════════════════════════════════════╗
echo ║  🧪 PROBAR ENVÍO DE EMAIL                             ║
echo ╚════════════════════════════════════════════════════════╝
echo.
set /p test_email=Ingresa tu email para recibir la prueba:
echo.
echo Enviando email de prueba a: %test_email%
echo.
curl -s -X POST -d "email=%test_email%" http://98.95.39.30/test_email.php
echo.
echo.
echo ══════════════════════════════════════════════════════
echo  VERIFICA TU CORREO:
echo ══════════════════════════════════════════════════════
echo  • Bandeja de entrada
echo  • Carpeta SPAM
echo  • Carpeta Promociones (Gmail)
echo.
pause
goto menu

:verificar
cls
echo ╔════════════════════════════════════════════════════════╗
echo ║  🔍 VERIFICAR ARCHIVOS LOCALES                        ║
echo ╚════════════════════════════════════════════════════════╝
echo.
echo Archivos PHP para el servidor:
echo ────────────────────────────────
dir /b solicitar_codigo_EMAIL.php 2>nul && echo ✅ solicitar_codigo_EMAIL.php || echo ❌ solicitar_codigo_EMAIL.php NO ENCONTRADO
dir /b solicitar_codigo_SMTP.php 2>nul && echo ✅ solicitar_codigo_SMTP.php || echo ❌ solicitar_codigo_SMTP.php NO ENCONTRADO
dir /b email_config.php 2>nul && echo ✅ email_config.php || echo ❌ email_config.php NO ENCONTRADO
dir /b test_email.php 2>nul && echo ✅ test_email.php || echo ❌ test_email.php NO ENCONTRADO
echo.
echo Guías y documentación:
echo ────────────────────────────────
dir /b RESUMEN_EMAIL_SETUP.md 2>nul && echo ✅ RESUMEN_EMAIL_SETUP.md || echo ❌ RESUMEN_EMAIL_SETUP.md NO ENCONTRADO
dir /b GUIA_CONFIGURAR_EMAIL.md 2>nul && echo ✅ GUIA_CONFIGURAR_EMAIL.md || echo ❌ GUIA_CONFIGURAR_EMAIL.md NO ENCONTRADO
dir /b COMANDOS_SSH_EMAIL.md 2>nul && echo ✅ COMANDOS_SSH_EMAIL.md || echo ❌ COMANDOS_SSH_EMAIL.md NO ENCONTRADO
echo.
echo Scripts de ayuda:
echo ────────────────────────────────
dir /b SUBIR_ARCHIVOS_EMAIL.bat 2>nul && echo ✅ SUBIR_ARCHIVOS_EMAIL.bat || echo ❌ SUBIR_ARCHIVOS_EMAIL.bat NO ENCONTRADO
dir /b PROBAR_EMAIL.bat 2>nul && echo ✅ PROBAR_EMAIL.bat || echo ❌ PROBAR_EMAIL.bat NO ENCONTRADO
dir /b instalar_phpmailer_completo.sh 2>nul && echo ✅ instalar_phpmailer_completo.sh || echo ❌ instalar_phpmailer_completo.sh NO ENCONTRADO
echo.
pause
goto menu

:probar_servidor
cls
echo ╔════════════════════════════════════════════════════════╗
echo ║  🌐 PROBAR SERVIDOR                                   ║
echo ╚════════════════════════════════════════════════════════╝
echo.
call PROBAR_SERVIDOR.bat
pause
goto menu

:salir
cls
echo.
echo ══════════════════════════════════════════════════════
echo  📧 RESUMEN DE LO QUE TIENES:
echo ══════════════════════════════════════════════════════
echo.
echo  ✅ Archivos PHP listos para subir
echo  ✅ Guías completas de configuración
echo  ✅ Scripts de ayuda automatizados
echo  ✅ Comandos SSH listos para copiar
echo.
echo  📋 SIGUIENTE PASO:
echo  ──────────────────────────────────────────────────────
echo  1. Lee RESUMEN_EMAIL_SETUP.md (empieza aquí)
echo  2. Elige opción simple o profesional
echo  3. Sube los archivos con WinSCP
echo  4. Prueba con PROBAR_EMAIL.bat
echo  5. ¡Disfruta el envío automático de emails! 🎉
echo.
echo ══════════════════════════════════════════════════════
echo.
pause
exit


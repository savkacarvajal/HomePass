# 📧 SISTEMA DE ENVÍO DE EMAILS - README

## 🎯 Propósito

Este sistema permite enviar **códigos de recuperación de contraseña por email** en lugar de mostrarlos en la aplicación, mejorando la seguridad y la experiencia del usuario.

---

## ⚡ INICIO RÁPIDO

### Opción 1: Quiero empezar YA (5 minutos)

```bash
1. Abre:      INICIO_RAPIDO_EMAIL.txt
2. O ejecuta: MENU_EMAIL.bat
3. Sigue los pasos de la "OPCIÓN SIMPLE"
```

### Opción 2: Quiero la versión profesional (20 minutos)

```bash
1. Lee:       RESUMEN_EMAIL_SETUP.md
2. Sigue:     Sección "OPCIÓN 2: PROFESIONAL"
3. Configura: PHPMailer + SMTP
```

---

## 📦 ESTRUCTURA DE ARCHIVOS

```
📧 SISTEMA DE EMAILS
│
├─📄 PHP (Para el servidor)
│  ├─ solicitar_codigo_EMAIL.php    ⭐ Simple
│  ├─ solicitar_codigo_SMTP.php     ⭐⭐⭐ Profesional
│  ├─ email_config.php              ⚙️ Configuración
│  └─ test_email.php                🧪 Pruebas
│
├─📚 GUÍAS
│  ├─ INICIO_RAPIDO_EMAIL.txt       🚀 EMPIEZA AQUÍ
│  ├─ RESUMEN_EMAIL_SETUP.md        📋 Resumen completo
│  ├─ GUIA_CONFIGURAR_EMAIL.md      📖 Guía detallada
│  ├─ COMANDOS_SSH_EMAIL.md         💻 Comandos SSH
│  ├─ INDICE_EMAIL_COMPLETO.md      📚 Índice
│  └─ README_EMAIL.md               📄 Este archivo
│
└─🔧 SCRIPTS
   ├─ MENU_EMAIL.bat                🎯 Menú principal
   ├─ PROBAR_EMAIL.bat              ✅ Probar envío
   ├─ SUBIR_ARCHIVOS_EMAIL.bat      📤 Guía WinSCP
   └─ instalar_phpmailer_completo.sh 📦 Instalador
```

---

## 🎓 RUTAS DE APRENDIZAJE

### 👶 Principiante
```
1. Lee:    INICIO_RAPIDO_EMAIL.txt
2. Usa:    Opción Simple (mail())
3. Tiempo: 5-10 minutos
4. Resultado: Código en email (puede ir a SPAM)
```

### 👨‍💻 Intermedio
```
1. Lee:    RESUMEN_EMAIL_SETUP.md
2. Usa:    Opción Profesional (PHPMailer + Gmail)
3. Tiempo: 20-30 minutos
4. Resultado: Emails profesionales que no van a SPAM
```

### 🧙 Avanzado
```
1. Lee:    GUIA_CONFIGURAR_EMAIL.md
2. Usa:    PHPMailer + SendGrid + Personalización
3. Tiempo: 1-2 horas
4. Resultado: Sistema profesional completo
```

---

## 🔄 FLUJO DEL SISTEMA

```
┌─────────────┐
│   Usuario   │
│   (App)     │
└──────┬──────┘
       │
       │ 1. Solicita código
       │    (email)
       ▼
┌─────────────────────────┐
│ solicitar_codigo.php    │
│ - Genera código         │
│ - Guarda en BD          │
│ - Envía email           │
└──────┬──────────────────┘
       │
       │ 2. Envía email
       ▼
┌─────────────────────────┐
│  Sistema de Email       │
│  - mail() simple        │
│  - PHPMailer + SMTP     │
└──────┬──────────────────┘
       │
       │ 3. Código en email
       ▼
┌─────────────────────────┐
│  Bandeja del Usuario    │
│  📧 Código: 12345       │
└─────────────────────────┘
```

---

## 🆚 COMPARACIÓN DE OPCIONES

| Característica | Opción Simple | Opción Profesional |
|----------------|---------------|-------------------|
| Tiempo setup | 5 min ⚡ | 20 min |
| Archivos | 1 | 3 |
| Instalación | Ninguna ✅ | Composer + PHPMailer |
| Configuración | Ninguna ✅ | Credenciales SMTP |
| Va a SPAM | Sí ⚠️ | No ✅ |
| Confiabilidad | 50% | 95% |
| Personalización | Básica | Completa |
| Para | Desarrollo | Producción ⭐ |

---

## 📧 DISEÑO DEL EMAIL

El usuario recibirá un email HTML profesional:

```
╔════════════════════════════════════════╗
║  🔐  Recuperación de Contraseña       ║
╚════════════════════════════════════════╝

Hola,

Has solicitado restablecer tu contraseña
en PNKCL IoT.

Tu código de verificación es:

       ┌───────────────┐
       │   1 2 3 4 5   │
       └───────────────┘

⚠️ IMPORTANTE:
  • Válido por 15 minutos
  • No compartir con nadie
  • Si no lo solicitaste, ignora este email

────────────────────────────────────────
© 2025 PNKCL IoT
```

---

## 🛠️ REQUISITOS

### En tu PC:
- ✅ Windows
- ✅ WinSCP instalado
- ✅ Acceso SSH al servidor

### En el servidor:
- ✅ PHP 7.0+ con función mail() (Opción Simple)
- ✅ Composer + PHPMailer (Opción Profesional)
- ✅ Puerto 587/465 abierto (Opción Profesional)

### Email:
- ✅ Cuenta Gmail (gratuita)
- ✅ O cuenta SendGrid (100 emails/día gratis)
- ✅ O cualquier proveedor SMTP

---

## 🚀 INSTALACIÓN RÁPIDA

### Opción Simple (5 minutos)

```bash
# 1. Subir archivo
WinSCP → Conectar → 98.95.39.30
Arrastrar: solicitar_codigo_EMAIL.php → solicitar_codigo.php

# 2. Probar
PROBAR_SERVIDOR.bat
```

### Opción Profesional (20 minutos)

```bash
# 1. Instalar PHPMailer en servidor
ssh ec2-user@98.95.39.30
cd /var/www/html
composer require phpmailer/phpmailer

# 2. Configurar credenciales
Editar: email_config.php
Cambiar: SMTP_USERNAME, SMTP_PASSWORD

# 3. Subir archivos
WinSCP:
  - email_config.php → /var/www/html/
  - solicitar_codigo_SMTP.php → /var/www/html/solicitar_codigo.php

# 4. Probar
PROBAR_EMAIL.bat
```

---

## 🧪 TESTING

### Probar desde Windows:
```cmd
PROBAR_EMAIL.bat
```

### Probar desde servidor:
```bash
curl -X POST -d "email=tumail@gmail.com" http://localhost/test_email.php
```

### Verificar logs:
```bash
sudo tail -f /var/log/php-fpm/error.log
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### ❌ Email no llega
1. Revisa carpeta SPAM
2. Verifica credenciales en `email_config.php`
3. Ejecuta `test_email.php`
4. Revisa logs: `sudo tail -f /var/log/php-fpm/error.log`

### ❌ Error SMTP connect() failed
1. Verifica que puerto 587 esté abierto
2. Verifica Host SMTP en `email_config.php`
3. Para Gmail, usa "Contraseña de aplicación"

### ❌ Error Authentication failed
1. Verifica usuario y contraseña
2. Para Gmail: https://myaccount.google.com/apppasswords
3. Para SendGrid: Verifica API Key

### ❌ PHPMailer no encontrado
1. `cd /var/www/html`
2. `composer require phpmailer/phpmailer`
3. Verifica: `ls -la vendor/phpmailer/`

---

## 📞 RECURSOS

### Guías:
- Inicio rápido: `INICIO_RAPIDO_EMAIL.txt`
- Resumen: `RESUMEN_EMAIL_SETUP.md`
- Detallada: `GUIA_CONFIGURAR_EMAIL.md`
- Comandos: `COMANDOS_SSH_EMAIL.md`

### Scripts:
- Menú: `MENU_EMAIL.bat`
- Probar: `PROBAR_EMAIL.bat`
- Instalar: `instalar_phpmailer_completo.sh`

### Enlaces:
- PHPMailer: https://github.com/PHPMailer/PHPMailer
- SendGrid: https://sendgrid.com/
- Gmail App Passwords: https://myaccount.google.com/apppasswords

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

### Desarrollo:
- [ ] Archivo `solicitar_codigo_EMAIL.php` subido
- [ ] Probado con `PROBAR_SERVIDOR.bat`
- [ ] Email recibido (aunque sea en SPAM)

### Producción:
- [ ] Composer instalado en servidor
- [ ] PHPMailer instalado
- [ ] `email_config.php` configurado
- [ ] `solicitar_codigo_SMTP.php` subido
- [ ] `test_email.php` probado exitosamente
- [ ] Email NO va a SPAM
- [ ] Código DEBUG removido
- [ ] Probado con usuarios reales

---

## 🎯 SIGUIENTE PASO

### Para empezar AHORA:
```
Ejecuta: MENU_EMAIL.bat
```

### Para leer primero:
```
Abre: INICIO_RAPIDO_EMAIL.txt
```

### Para ir directo al grano:
```
1. Abre WinSCP
2. Sube solicitar_codigo_EMAIL.php
3. Ejecuta PROBAR_SERVIDOR.bat
4. ¡Listo!
```

---

## 📊 ESTADÍSTICAS

- **Archivos creados:** 13
- **Guías:** 5
- **Scripts:** 4
- **Archivos PHP:** 4
- **Tiempo de lectura completa:** 2 horas
- **Tiempo de implementación:** 5 minutos (simple) o 20 minutos (profesional)

---

## 🎉 ¡LISTO!

Todo el sistema está configurado y documentado.

**Próximo paso:** Ejecuta `MENU_EMAIL.bat` o lee `INICIO_RAPIDO_EMAIL.txt`

¡Disfruta del envío automático de emails! 📧✨

---

**Versión:** 1.0  
**Fecha:** 2025-11-07  
**Proyecto:** PNKCL IoT  
**Autor:** Sistema Automatizado


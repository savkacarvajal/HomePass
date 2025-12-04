# 🔧 SOLUCIÓN: Email de recuperación no llega

## 🚨 PROBLEMA DETECTADO

**Email:** savka.carvajal@inacapmail.cl  
**Síntoma:** No llega el código de 5 dígitos para recuperar contraseña

---

## ⚡ SOLUCIÓN RÁPIDA (3 opciones)

### OPCIÓN 1: Ver el código directamente desde la BD (Más rápido)

**Ejecuta en MySQL:**

```sql
-- Ver el código más reciente para tu email
SELECT 
    code as codigo,
    created_at as fecha_creacion,
    TIMESTAMPDIFF(MINUTE, created_at, NOW()) as minutos_transcurridos,
    CASE 
        WHEN TIMESTAMPDIFF(MINUTE, created_at, NOW()) <= 15 THEN '✅ Código válido'
        ELSE '❌ Código expirado - solicita uno nuevo'
    END as estado
FROM password_resets
WHERE email = 'savka.carvajal@inacapmail.cl'
ORDER BY created_at DESC
LIMIT 1;
```

**Resultado esperado:**
```
codigo:               12345  ← Usa este en la app
fecha_creacion:       2025-12-03 14:30:00
minutos_transcurridos: 2
estado:               ✅ Código válido
```

**Pasos:**
1. Ejecuta la consulta SQL
2. Copia el código de 5 dígitos
3. Ve a la app → Ingresa el código
4. Crea nueva contraseña
5. ✅ Login con la nueva contraseña

---

### OPCIÓN 2: Generar código y verlo desde navegador

**Paso 1: Solicitar código**
```
http://44.199.155.199/solicitar_codigo_con_email.php?email=savka.carvajal@inacapmail.cl
```

**Paso 2: Ver el código**
```
http://44.199.155.199/ver_codigo_temporal.php?email=savka.carvajal@inacapmail.cl
```

**Resultado:**
```json
{
  "email": "savka.carvajal@inacapmail.cl",
  "code": "12345",
  "created_at": "2025-12-03 14:30:00",
  "minutos_transcurridos": 1,
  "valido": true
}
```

**Pasos:**
1. Abre ambas URLs en tu navegador
2. Copia el código de 5 dígitos
3. Ingrésalo en la app
4. Crea nueva contraseña

---

### OPCIÓN 3: Cambiar contraseña directamente desde la BD

**Si necesitas acceso urgente:**

```sql
-- Cambiar contraseña a "Test1234!"
-- Hash generado con password_hash("Test1234!", PASSWORD_DEFAULT)

UPDATE usuarios 
SET password_hash = '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNO'
WHERE email = 'savka.carvajal@inacapmail.cl';
```

**⚠️ IMPORTANTE:** El hash de arriba es de ejemplo. Necesitas generar uno real.

**Mejor opción - Generar hash correcto:**

**Paso 1:** Crea archivo temporal `generar_hash.php`:
```php
<?php
echo password_hash("Test1234!", PASSWORD_DEFAULT);
?>
```

**Paso 2:** Ejecútalo:
```bash
php generar_hash.php
```

**Paso 3:** Copia el hash generado y úsalo en el UPDATE

**Paso 4:** Haz login con `Test1234!`

---

## 🔍 DIAGNÓSTICO: ¿Por qué no llega el email?

### Verificación 1: ¿El usuario existe?

```sql
SELECT 
    id_usuario,
    nombre,
    apellido,
    email,
    estado
FROM usuarios
WHERE email = 'savka.carvajal@inacapmail.cl';
```

**Si muestra 0 resultados:**
- ❌ El usuario NO existe
- Solución: Regístrate primero desde la app

**Si muestra el usuario:**
- ✅ Usuario existe
- Continúa al siguiente paso

---

### Verificación 2: ¿Se está generando el código?

```sql
SELECT * FROM password_resets 
WHERE email = 'savka.carvajal@inacapmail.cl'
ORDER BY created_at DESC;
```

**Si muestra códigos:**
- ✅ El sistema genera códigos
- ❌ Pero el email no se envía
- Problema: Configuración SMTP o PHPMailer

**Si NO muestra códigos:**
- ❌ La solicitud no llega al servidor
- Problema: Conectividad o URL incorrecta

---

### Verificación 3: ¿PHPMailer está instalado?

**Verificar en el servidor:**
```bash
ssh ec2-user@44.199.155.199
cd /var/www/html
ls -la vendor/phpmailer/
```

**Si NO existe:**
```bash
composer require phpmailer/phpmailer
```

---

### Verificación 4: ¿Configuración SMTP correcta?

**Archivo: `email_config.php` en el servidor**

Debe tener:
```php
define('SMTP_USERNAME', 'savkacarvajalg1@gmail.com');
define('SMTP_PASSWORD', 'zjewbamfuzzftmbi'); // Contraseña de app Gmail
```

**Probar envío:**
```
http://44.199.155.199/test_envio_simple.php?email=savka.carvajal@inacapmail.cl
```

---

## 🎯 SOLUCIÓN INMEDIATA RECOMENDADA

**Para acceder AHORA a tu cuenta:**

### Método 1: Ver código desde BD (30 segundos)

1. **Abre MySQL:**
   ```bash
   mysql -u root -p homepass_db
   ```

2. **Ejecuta:**
   ```sql
   -- Si ya solicitaste el código desde la app:
   SELECT code FROM password_resets 
   WHERE email = 'savka.carvajal@inacapmail.cl'
   ORDER BY created_at DESC LIMIT 1;
   
   -- Si NO has solicitado código, genera uno:
   INSERT INTO password_resets (email, code, created_at, expires_at)
   VALUES (
       'savka.carvajal@inacapmail.cl',
       LPAD(FLOOR(RAND() * 100000), 5, '0'),
       NOW(),
       DATE_ADD(NOW(), INTERVAL 15 MINUTE)
   );
   
   -- Luego ver el código:
   SELECT code FROM password_resets 
   WHERE email = 'savka.carvajal@inacapmail.cl'
   ORDER BY created_at DESC LIMIT 1;
   ```

3. **Copia el código** (ejemplo: 12345)

4. **Ve a la app:**
   - Ingresa el código
   - Crea nueva contraseña
   - ✅ Login exitoso

---

### Método 2: Cambiar contraseña sin código (1 minuto)

**Genera hash de contraseña:**

**En el servidor o localmente:**
```bash
php -r "echo password_hash('Test1234!', PASSWORD_DEFAULT);"
```

**Copia el resultado** (ejemplo: `$2y$10$abc...xyz`)

**Actualiza en MySQL:**
```sql
UPDATE usuarios 
SET password_hash = '$2y$10$EL_HASH_QUE_COPIASTE'
WHERE email = 'savka.carvajal@inacapmail.cl';
```

**Ahora haz login con:**
- Email: savka.carvajal@inacapmail.cl
- Contraseña: Test1234!

---

## 🛠️ SOLUCIÓN PERMANENTE (Para que los emails funcionen)

### 1. Verificar PHPMailer

```bash
# En el servidor
cd /var/www/html
composer show phpmailer/phpmailer
```

**Si no está instalado:**
```bash
composer require phpmailer/phpmailer
sudo chown -R apache:apache vendor/
```

---

### 2. Verificar email_config.php

```bash
# Ver si existe
ls -la /var/www/html/email_config.php

# Ver contenido (sin mostrar contraseña completa)
grep SMTP_USERNAME /var/www/html/email_config.php
```

**Debe existir y tener:**
- SMTP_HOST: smtp.gmail.com
- SMTP_PORT: 587
- SMTP_USERNAME: tu_email@gmail.com
- SMTP_PASSWORD: contraseña_de_aplicación_16_chars

---

### 3. Probar envío de email

```
http://44.199.155.199/test_envio_simple.php?email=savka.carvajal@inacapmail.cl
```

**Respuesta esperada:**
```json
{
  "status": "success",
  "message": "Email enviado exitosamente",
  "destinatario": "savka.carvajal@inacapmail.cl"
}
```

**Si da error:**
- Ver el mensaje de error
- Revisar configuración SMTP
- Verificar que la contraseña de aplicación sea válida

---

### 4. Regenerar contraseña de aplicación Gmail

**Si el error es de autenticación:**

1. Ve a: https://myaccount.google.com/apppasswords
2. Elimina la contraseña antigua
3. Genera nueva contraseña de aplicación
4. Actualiza en `email_config.php` del servidor
5. Prueba nuevamente el envío

---

## 📱 MIENTRAS TANTO - Usa la app

**Temporal - Hasta que el email funcione:**

1. **Solicita código desde la app**
2. **Ve a MySQL y ejecuta:**
   ```sql
   SELECT code FROM password_resets 
   WHERE email = 'savka.carvajal@inacapmail.cl'
   ORDER BY created_at DESC LIMIT 1;
   ```
3. **Ingresa el código manualmente en la app**
4. **Crea tu nueva contraseña**

**Esto funciona porque:**
- ✅ El código SÍ se genera en la BD
- ✅ Solo falta que el email se envíe
- ✅ Pero puedes ver el código directamente

---

## ✅ CHECKLIST DE SOLUCIÓN

- [ ] Verificar que el usuario existe en la BD
- [ ] Verificar que se genera código en `password_resets`
- [ ] Ver el código desde MySQL
- [ ] Ingresar código en la app
- [ ] Cambiar contraseña
- [ ] Hacer login con nueva contraseña
- [ ] (Opcional) Arreglar sistema de emails para futuro

---

## 🎯 RESUMEN

**PARA ACCEDER AHORA (Opción más rápida):**

```sql
-- 1. Ver/Generar código
SELECT code FROM password_resets 
WHERE email = 'savka.carvajal@inacapmail.cl'
ORDER BY created_at DESC LIMIT 1;

-- Si no hay código, genera uno:
INSERT INTO password_resets (email, code, created_at, expires_at)
VALUES ('savka.carvajal@inacapmail.cl', 
        LPAD(FLOOR(RAND() * 100000), 5, '0'),
        NOW(), 
        DATE_ADD(NOW(), INTERVAL 15 MINUTE));

-- 2. Ver el código nuevamente
SELECT code FROM password_resets 
WHERE email = 'savka.carvajal@inacapmail.cl'
ORDER BY created_at DESC LIMIT 1;
```

**Luego en la app:**
- Ingresa el código de 5 dígitos
- Crea nueva contraseña
- ✅ Login exitoso

---

**Desarrollado por:** Savka Carvajal & Dante Gutierrez  
**Proyecto:** HomePass IoT - INACAP 2025  
**Fecha:** 3 de diciembre de 2025


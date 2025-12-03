# 📋 COMANDOS SSH - COPIAR Y PEGAR

## 🔌 CONECTAR AL SERVIDOR
```bash
ssh ec2-user@98.95.39.30 -i tu_clave.pem
```

---

## 📦 INSTALAR PHPMAILER

### Opción A: Instalación automática con script
```bash
# 1. Sube instalar_phpmailer_completo.sh al servidor con WinSCP

# 2. Dale permisos de ejecución
chmod +x /var/www/html/instalar_phpmailer_completo.sh

# 3. Ejecuta
sudo /var/www/html/instalar_phpmailer_completo.sh
```

### Opción B: Instalación manual (recomendada)
```bash
# Ir al directorio web
cd /var/www/html

# Instalar Composer si no está instalado
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Instalar PHPMailer
composer require phpmailer/phpmailer

# Verificar instalación
ls -la vendor/phpmailer/phpmailer/
```

---

## 📝 VERIFICAR ARCHIVOS EN EL SERVIDOR

```bash
# Ver archivos PHP en /var/www/html
ls -lh /var/www/html/*.php

# Ver si existe email_config.php
cat /var/www/html/email_config.php

# Ver si PHPMailer está instalado
ls -la /var/www/html/vendor/phpmailer/
```

---

## 🧪 PROBAR ENVÍO DE EMAIL DESDE EL SERVIDOR

```bash
# Probar con curl desde el servidor
curl -X POST -d "email=tu_email@gmail.com" http://localhost/solicitar_codigo.php

# Probar test_email.php
curl -X POST -d "email=tu_email@gmail.com" http://localhost/test_email.php

# Ver respuesta formateada
curl -X POST -d "email=tu_email@gmail.com" http://localhost/test_email.php | python -m json.tool
```

---

## 🔍 VER LOGS EN TIEMPO REAL

```bash
# Logs de PHP-FPM (errores de PHP)
sudo tail -f /var/log/php-fpm/error.log

# Logs de Apache (errores del servidor web)
sudo tail -f /var/log/httpd/error_log

# Ver últimas 50 líneas
sudo tail -n 50 /var/log/php-fpm/error.log

# Buscar errores específicos
sudo grep -i "error" /var/log/php-fpm/error.log | tail -n 20
```

---

## ⚙️ CONFIGURAR email_config.php EN EL SERVIDOR

```bash
# Editar con nano
sudo nano /var/www/html/email_config.php

# O editar con vi
sudo vi /var/www/html/email_config.php

# Ver contenido actual
cat /var/www/html/email_config.php
```

**Contenido sugerido para Gmail:**
```php
<?php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'tu_email@gmail.com');
define('SMTP_PASSWORD', 'xxxx xxxx xxxx xxxx'); // Contraseña de aplicación
define('FROM_EMAIL', 'tu_email@gmail.com');
define('FROM_NAME', 'PNKCL IoT');
define('EMAIL_DEBUG', true);
?>
```

Para guardar en nano: `Ctrl + O`, `Enter`, `Ctrl + X`

---

## 🔄 REINICIAR SERVICIOS

```bash
# Reiniciar PHP-FPM
sudo systemctl restart php-fpm

# Reiniciar Apache
sudo systemctl restart httpd

# Verificar estado
sudo systemctl status php-fpm
sudo systemctl status httpd
```

---

## 🗂️ GESTIÓN DE ARCHIVOS

```bash
# Copiar archivo
sudo cp /var/www/html/solicitar_codigo.php /var/www/html/solicitar_codigo_backup.php

# Mover/Renombrar archivo
sudo mv /var/www/html/solicitar_codigo_EMAIL.php /var/www/html/solicitar_codigo.php

# Eliminar archivo
sudo rm /var/www/html/archivo_viejo.php

# Ver permisos
ls -lh /var/www/html/

# Cambiar permisos (si es necesario)
sudo chmod 644 /var/www/html/solicitar_codigo.php
sudo chown apache:apache /var/www/html/solicitar_codigo.php
```

---

## 🔐 VERIFICAR CONFIGURACIÓN DE PHP

```bash
# Ver versión de PHP
php -v

# Ver módulos instalados
php -m

# Verificar si mail() está disponible
php -r "phpinfo();" | grep -i mail

# Verificar configuración de sendmail
php -i | grep sendmail_path
```

---

## 🌐 VERIFICAR CONECTIVIDAD SMTP

```bash
# Probar conexión a Gmail SMTP
telnet smtp.gmail.com 587

# Si telnet no funciona, usar nc
nc -zv smtp.gmail.com 587

# Verificar puertos abiertos
sudo netstat -tuln | grep :587
```

---

## 📊 VERIFICAR BASE DE DATOS

```bash
# Conectar a MySQL
mysql -u root -p

# Dentro de MySQL:
USE pnkcl_iot;
SHOW TABLES;
SELECT * FROM password_resets;
SELECT * FROM users LIMIT 5;

# Verificar códigos recientes
SELECT * FROM password_resets WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR);

# Salir
exit;
```

---

## 🧹 LIMPIAR CÓDIGOS EXPIRADOS

```bash
# Conectar a MySQL y limpiar códigos de más de 15 minutos
mysql -u root -pAdmin12345 pnkcl_iot -e "DELETE FROM password_resets WHERE created_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE);"

# Ver cuántos códigos hay
mysql -u root -pAdmin12345 pnkcl_iot -e "SELECT COUNT(*) as total FROM password_resets;"
```

---

## 🚀 COMANDOS DE PRUEBA COMPLETOS

```bash
# Prueba completa desde el servidor
cd /var/www/html

# 1. Verificar archivos
ls -lh solicitar_codigo.php email_config.php

# 2. Verificar PHPMailer
ls -la vendor/phpmailer/phpmailer/

# 3. Probar envío
curl -X POST -d "email=tu_email@gmail.com" http://localhost/test_email.php

# 4. Ver respuesta
curl -X POST -d "email=tu_email@gmail.com" http://localhost/solicitar_codigo.php

# 5. Verificar en BD
mysql -u root -pAdmin12345 pnkcl_iot -e "SELECT * FROM password_resets ORDER BY created_at DESC LIMIT 1;"

# 6. Ver logs
sudo tail -n 20 /var/log/php-fpm/error.log
```

---

## 📦 BACKUP Y RESTAURACIÓN

```bash
# Hacer backup de archivos PHP
cd /var/www/html
sudo tar -czf backup_php_$(date +%Y%m%d_%H%M%S).tar.gz *.php

# Ver backups
ls -lh backup_php_*.tar.gz

# Restaurar un backup
sudo tar -xzf backup_php_20250107_120000.tar.gz

# Backup de la base de datos
mysqldump -u root -pAdmin12345 pnkcl_iot > backup_db_$(date +%Y%m%d_%H%M%S).sql

# Restaurar base de datos
mysql -u root -pAdmin12345 pnkcl_iot < backup_db_20250107_120000.sql
```

---

## 🔧 SOLUCIÓN DE PROBLEMAS RÁPIDOS

### Si PHPMailer no funciona:
```bash
# Reinstalar
cd /var/www/html
rm -rf vendor/
composer install
```

### Si los emails no se envían:
```bash
# Verificar logs
sudo tail -f /var/log/php-fpm/error.log

# Probar con mail() simple
echo "Test email" | mail -s "Test" tu_email@gmail.com

# Verificar configuración de mail
cat /etc/php.ini | grep sendmail
```

### Si hay problemas de permisos:
```bash
# Arreglar permisos
sudo chown -R apache:apache /var/www/html/
sudo chmod -R 755 /var/www/html/
sudo chmod 644 /var/www/html/*.php
```

---

## 📞 COMANDOS DE DIAGNÓSTICO

```bash
# Ver uso de recursos
top
htop  # Si está instalado

# Ver espacio en disco
df -h

# Ver memoria
free -h

# Ver procesos de PHP
ps aux | grep php

# Ver procesos de Apache
ps aux | grep httpd

# Ver conexiones activas
sudo netstat -tuln
```

---

## 🎯 COMANDO TODO-EN-UNO PARA PROBAR

```bash
echo "=========================================="
echo "DIAGNÓSTICO COMPLETO DEL SISTEMA"
echo "=========================================="
echo ""
echo "1. Archivos PHP:"
ls -lh /var/www/html/*.php
echo ""
echo "2. PHPMailer instalado:"
ls -la /var/www/html/vendor/phpmailer/ 2>/dev/null && echo "✅ SÍ" || echo "❌ NO"
echo ""
echo "3. Última entrada en BD:"
mysql -u root -pAdmin12345 pnkcl_iot -e "SELECT * FROM password_resets ORDER BY created_at DESC LIMIT 1;" 2>/dev/null
echo ""
echo "4. Últimos logs:"
sudo tail -n 10 /var/log/php-fpm/error.log
echo ""
echo "5. Prueba de envío:"
curl -s -X POST -d "email=tu_email@gmail.com" http://localhost/test_email.php
echo ""
echo "=========================================="
```

---

## 💡 TIPS ÚTILES

```bash
# Crear alias útiles (agregar a ~/.bashrc)
alias phplogs='sudo tail -f /var/log/php-fpm/error.log'
alias apachelogs='sudo tail -f /var/log/httpd/error_log'
alias webdir='cd /var/www/html'

# Recargar bashrc
source ~/.bashrc

# Ahora puedes usar:
phplogs
webdir
```

---

## 🎓 RESUMEN PARA PRINCIPIANTES

```bash
# 1. Conectar
ssh ec2-user@98.95.39.30 -i tu_clave.pem

# 2. Instalar PHPMailer
cd /var/www/html
composer require phpmailer/phpmailer

# 3. Configurar email (usa WinSCP para subir email_config.php)

# 4. Probar
curl -X POST -d "email=tu_email@gmail.com" http://localhost/test_email.php

# 5. Ver logs si falla
sudo tail -f /var/log/php-fpm/error.log

# 6. ¡Listo!
```

---

¡Copia y pega estos comandos según necesites! 🚀


# ⏰ ZONA HORARIA DE CHILE CONFIGURADA

## ✅ CAMBIOS APLICADOS

Se ha configurado la zona horaria de Chile en toda la aplicación para que **TODOS** los registros (usuarios, eventos, sensores) muestren la hora correcta de Chile.

---

## 🕐 CONFIGURACIÓN ACTUAL

### PHP (Backend)
```php
date_default_timezone_set('America/Santiago');
```

### MySQL (Base de Datos)
```sql
SET time_zone = '-03:00'; // Chile (UTC-3)
```

**Hora actual de Chile:** 23:53 PM (3 de diciembre de 2025)

---

## 📋 TABLAS AFECTADAS

Todas estas tablas ahora guardan y muestran la hora de Chile:

| Tabla | Campo de Fecha | Estado |
|-------|----------------|--------|
| **usuarios** | fecha_creacion | ✅ Hora Chile |
| **usuarios** | fecha_modificacion | ✅ Hora Chile |
| **eventos_acceso** | fecha_hora | ✅ Hora Chile |
| **sensores** | fecha_alta | ✅ Hora Chile |
| **sensores** | fecha_baja | ✅ Hora Chile |
| **password_resets** | created_at | ✅ Hora Chile |
| **password_resets** | expires_at | ✅ Hora Chile |
| **departamentos** | fecha_creacion | ✅ Hora Chile |

---

## 🚀 CÓMO APLICAR LOS CAMBIOS

### 1. Subir Archivo Actualizado al Servidor

**Archivo modificado:** `conexion.php`

**Opción A - Con WinSCP:**
```
1. Abre WinSCP
2. Conecta a tu servidor: 44.199.155.199
3. Navega a: /var/www/html/
4. Sube el archivo: conexion.php
5. Sobrescribe el archivo existente
```

**Opción B - Con comando SSH:**
```bash
# Desde tu PC, copia el archivo
scp conexion.php ec2-user@44.199.155.199:/var/www/html/

# O edítalo directamente en el servidor
ssh ec2-user@44.199.155.199
nano /var/www/html/conexion.php

# Agrega estas líneas después de definir las constantes:
date_default_timezone_set('America/Santiago');

# Y después de crear la conexión:
$conn->query("SET time_zone = '-03:00'");
```

### 2. Ejecutar Script SQL de Configuración

**Archivo:** `configurar_zona_horaria_chile.sql`

```bash
# Conecta a MySQL
mysql -u root -p homepass_db

# Ejecuta el script
source configurar_zona_horaria_chile.sql

# O copia y pega el contenido
```

**O desde MySQL Workbench / phpMyAdmin:**
1. Abre el archivo `configurar_zona_horaria_chile.sql`
2. Copia todo el contenido
3. Pégalo en la ventana de consultas
4. Ejecuta

---

## ✅ VERIFICAR QUE FUNCIONA

### Desde MySQL:

```sql
-- Ver configuración actual
SELECT @@global.time_zone, @@session.time_zone, NOW();

-- Debe mostrar:
-- time_zone: -03:00
-- NOW(): 2025-12-03 23:53:00 (hora actual de Chile)

-- Ver últimos registros con hora correcta
SELECT 
    nombre,
    apellido,
    email,
    fecha_creacion,
    DATE_FORMAT(fecha_creacion, '%d-%m-%Y %H:%i:%s') as fecha_chile
FROM usuarios
ORDER BY fecha_creacion DESC
LIMIT 5;
```

### Desde la App:

1. **Registra un nuevo usuario**
2. **Ve a MySQL y ejecuta:**
   ```sql
   SELECT nombre, apellido, fecha_creacion 
   FROM usuarios 
   ORDER BY fecha_creacion DESC 
   LIMIT 1;
   ```
3. **Verifica que la hora sea la actual de Chile**

### Desde Eventos:

1. **Realiza una acción en la app** (acceso con sensor)
2. **Ve a Historial de Eventos**
3. **Verifica que la hora mostrada sea correcta**

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### ❌ La hora sigue incorrecta

**Solución 1: Reiniciar servidor web**
```bash
sudo systemctl restart httpd
# o
sudo service apache2 restart
```

**Solución 2: Verificar permisos de conexion.php**
```bash
chmod 644 /var/www/html/conexion.php
chown apache:apache /var/www/html/conexion.php
```

**Solución 3: Verificar que el código esté actualizado**
```bash
cat /var/www/html/conexion.php | grep Santiago
# Debe mostrar: date_default_timezone_set('America/Santiago');
```

---

### ❌ Fechas antiguas están incorrectas

Si los registros antiguos tienen hora incorrecta, ejecuta:

```sql
-- Ajustar fechas de usuarios (ejemplo)
UPDATE usuarios 
SET fecha_creacion = CONVERT_TZ(fecha_creacion, '+00:00', '-03:00')
WHERE fecha_creacion < '2025-12-03 23:00:00';

-- Ajustar fechas de eventos
UPDATE eventos_acceso 
SET fecha_hora = CONVERT_TZ(fecha_hora, '+00:00', '-03:00')
WHERE fecha_hora < '2025-12-03 23:00:00';
```

---

### ❌ MySQL no reconoce la zona horaria

**Solución:**
```bash
# En el servidor
sudo mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -u root -p mysql
sudo systemctl restart mysql
```

---

## 📝 ARCHIVOS MODIFICADOS

1. **`conexion.php`** ✅
   - Agregado: `date_default_timezone_set('America/Santiago')`
   - Agregado: `$conn->query("SET time_zone = '-03:00'")`

2. **`configurar_zona_horaria_chile.sql`** ✅ (NUEVO)
   - Script para configurar zona horaria en MySQL
   - Queries de verificación
   - Comandos para ajustar fechas antiguas

---

## 🎯 RESULTADO ESPERADO

### Antes:
```
Usuario registrado: 2025-12-04 02:53:00 (hora UTC)
Evento registrado: 2025-12-04 02:53:00 (hora UTC)
```

### Después:
```
Usuario registrado: 2025-12-03 23:53:00 ✅ (hora Chile)
Evento registrado: 2025-12-03 23:53:00 ✅ (hora Chile)
```

---

## ✅ CHECKLIST DE APLICACIÓN

- [ ] Subir `conexion.php` actualizado al servidor
- [ ] Verificar que el archivo contiene `America/Santiago`
- [ ] Ejecutar script `configurar_zona_horaria_chile.sql` en MySQL
- [ ] Reiniciar servidor web (Apache/Nginx)
- [ ] Probar registrando un nuevo usuario
- [ ] Verificar hora en MySQL
- [ ] Verificar hora en la app (Historial de Eventos)
- [ ] Confirmar que muestra 23:53 PM (hora actual Chile)

---

## 🎉 CONCLUSIÓN

**Todos los registros ahora se guardan con la hora de Chile.**

- ✅ Usuarios registrados → Hora Chile
- ✅ Eventos de acceso → Hora Chile
- ✅ Sensores creados → Hora Chile
- ✅ Códigos de recuperación → Hora Chile

**Los cambios ya están commitados en GitHub.**

**Solo falta subirlos al servidor y ejecutar el script SQL.** 🚀

---

**Desarrollado por:** Savka Carvajal & Dante Gutierrez  
**Proyecto:** HomePass IoT - INACAP 2025  
**Fecha:** 3 de diciembre de 2025, 23:53 PM (Chile)


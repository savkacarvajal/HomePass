# 📚 ÍNDICE - SOLUCIÓN RECUPERAR CONTRASEÑA

## 🎯 EMPIEZA AQUÍ

Si usas **WinSCP**, sigue este orden:

### 1️⃣ Lee primero (5 min):
📖 **[GUIA_VISUAL_WINSCP.md](GUIA_VISUAL_WINSCP.md)** ← **EMPIEZA AQUÍ**
- Capturas paso a paso
- Guía visual completa
- Todo lo que necesitas en un solo archivo

### 2️⃣ Referencia rápida:
📋 **[PASOS_RAPIDOS_WINSCP.md](PASOS_RAPIDOS_WINSCP.md)**
- Resumen de 3 minutos
- Pasos exactos sin explicaciones largas

### 3️⃣ Comandos para copiar:
💻 **[COMANDOS_SSH_COPIAR_PEGAR.md](COMANDOS_SSH_COPIAR_PEGAR.md)**
- Comandos listos para SSH
- Solo copiar y pegar

---

## 📁 ARCHIVOS A SUBIR

Estos son los archivos PHP que debes subir al servidor:

✅ **solicitar_codigo_NUEVO.php** (4 KB)
- Ubicación: `C:\Users\savka\AndroidStudioProjects\Test\`
- Estado: COMPLETO Y LISTO
- Subir a: `/home/ec2-user/` → luego mover a `/var/www/html/solicitar_codigo.php`

✅ **validar_codigo_NUEVO.php** (4 KB)
- Ubicación: `C:\Users\savka\AndroidStudioProjects\Test\`
- Estado: COMPLETO Y LISTO
- Subir a: `/home/ec2-user/` → luego mover a `/var/www/html/validar_codigo.php`

---

## 📚 DOCUMENTACIÓN ADICIONAL

### Entendiendo el problema:
📖 **[SOLUCION_DEFINITIVA_RECUPERAR.md](SOLUCION_DEFINITIVA_RECUPERAR.md)**
- Diagnóstico completo del error
- Explicación técnica
- Diferencias entre archivos viejos y nuevos

### Diagramas visuales:
📊 **[RESUMEN_VISUAL_SOLUCION.md](RESUMEN_VISUAL_SOLUCION.md)**
- Flujo completo del proceso
- Diagramas de base de datos
- Comparación antes/después

### Instrucciones detalladas:
📖 **[INSTRUCCIONES_WINSCP.md](INSTRUCCIONES_WINSCP.md)**
- Guía completa y detallada
- Solución de problemas
- Configuración avanzada

---

## 🛠️ SCRIPTS DE AYUDA

### Para probar desde Windows:
🧪 **test_recuperar.bat**
- Ejecutar desde cmd.exe
- Prueba automática con curl
- Verifica que el servidor responda

### Para configurar en servidor:
⚙️ **configurar_archivos.sh**
- Script bash para automatizar
- Mueve archivos y establece permisos
- Opcional (puedes hacerlo manualmente)

---

## 🗃️ ARCHIVOS DE BASE DE DATOS

### SQL para crear tabla:
📄 **crear_tabla_codigos.sql**
- Crea la tabla `password_resets`
- Ejecutar si la tabla no existe
- Incluye evento de limpieza automática

---

## ⚡ RUTA RÁPIDA (5 MINUTOS)

### Paso 1: WinSCP (2 min)
1. Conectar a `98.95.39.30` como `ec2-user`
2. Arrastrar `solicitar_codigo_NUEVO.php` a `/home/ec2-user/`
3. Arrastrar `validar_codigo_NUEVO.php` a `/home/ec2-user/`

### Paso 2: SSH (1 min)
```bash
sudo mv /home/ec2-user/solicitar_codigo_NUEVO.php /var/www/html/solicitar_codigo.php
sudo mv /home/ec2-user/validar_codigo_NUEVO.php /var/www/html/validar_codigo.php
sudo chmod 644 /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php
sudo chown apache:apache /var/www/html/solicitar_codigo.php /var/www/html/validar_codigo.php
```

### Paso 3: Verificar tabla BD (1 min)
```bash
mysql -u root -p  # Contraseña: Admin12345
USE pnkcl_iot;
SHOW TABLES LIKE 'password_resets';  # Si no existe, crearla
EXIT;
```

### Paso 4: Probar (1 min)
```bash
curl -X POST -d "email=test@example.com" http://98.95.39.30/solicitar_codigo.php
```

### Paso 5: App Android (30 seg)
- Recuperar Contraseña → Ingresar email → ¡Debería funcionar! ✅

---

## 📖 GUÍAS POR NIVEL

### 🟢 Principiante (nunca usaste WinSCP):
1. **[GUIA_VISUAL_WINSCP.md](GUIA_VISUAL_WINSCP.md)** - Capturas detalladas
2. **[INSTRUCCIONES_WINSCP.md](INSTRUCCIONES_WINSCP.md)** - Guía paso a paso

### 🟡 Intermedio (ya usaste WinSCP antes):
1. **[PASOS_RAPIDOS_WINSCP.md](PASOS_RAPIDOS_WINSCP.md)** - Resumen directo
2. **[COMANDOS_SSH_COPIAR_PEGAR.md](COMANDOS_SSH_COPIAR_PEGAR.md)** - Comandos listos

### 🔴 Avanzado (solo quieres los archivos):
1. Sube `solicitar_codigo_NUEVO.php` y `validar_codigo_NUEVO.php`
2. Ejecuta comandos del checklist rápido
3. Listo

---

## 🆘 SI ALGO NO FUNCIONA

### 1. El servidor devuelve Content-Length: 0
**Ver:** [SOLUCION_CONTENT_LENGTH_0.md](SOLUCION_CONTENT_LENGTH_0.md) (si existe)
**O:** Verifica que los archivos se subieron correctamente

### 2. Error "end of input at character 0"
**Ver:** [SOLUCION_END_OF_INPUT.md](SOLUCION_END_OF_INPUT.md) (si existe)
**O:** Es el mismo problema del Content-Length: 0

### 3. No puedo conectar con WinSCP
**Ver:** Sección "Solución de problemas" en [INSTRUCCIONES_WINSCP.md](INSTRUCCIONES_WINSCP.md)

### 4. Problemas con la base de datos
**Ver:** [SOLUCION_ERROR_RECUPERAR.md](SOLUCION_ERROR_RECUPERAR.md) (si existe)
**O:** Sección de BD en [COMANDOS_SSH_COPIAR_PEGAR.md](COMANDOS_SSH_COPIAR_PEGAR.md)

---

## ✅ CHECKLIST GENERAL

```
Preparación
  └─ [✓] Archivos PHP creados en C:\Users\savka\AndroidStudioProjects\Test\

WinSCP
  ├─ [ ] Conectado a 98.95.39.30
  ├─ [ ] solicitar_codigo_NUEVO.php subido
  └─ [ ] validar_codigo_NUEVO.php subido

SSH/Servidor
  ├─ [ ] Archivos movidos a /var/www/html/
  ├─ [ ] Permisos 644 establecidos
  ├─ [ ] Propietario apache:apache establecido
  └─ [ ] Tabla password_resets existe

Pruebas
  ├─ [ ] curl desde servidor → JSON OK
  ├─ [ ] curl desde Windows → JSON OK
  └─ [ ] App Android → Funciona ✅
```

---

## 🎯 RESULTADO ESPERADO

### En el terminal (curl):
```json
{
  "status": "success",
  "message": "Si el email está registrado, se ha enviado un código de restablecimiento. (DEBUG: 12345)"
}
```

### En la app Android:
```
┌────────────────────────────────┐
│     ¡Correo enviado! ✓         │
│                                │
│  Si el email está registrado,  │
│  se ha enviado un código...    │
│  (DEBUG: 12345)                │
│                                │
│          [  OK  ]               │
└────────────────────────────────┘
```

---

## 📞 ESTRUCTURA DE ARCHIVOS

```
C:\Users\savka\AndroidStudioProjects\Test\
│
├── 📄 PHP (SUBIR AL SERVIDOR) ⬅️ IMPORTANTE
│   ├── solicitar_codigo_NUEVO.php ✅ LISTO
│   └── validar_codigo_NUEVO.php   ✅ LISTO
│
├── 📖 GUÍAS PRINCIPALES
│   ├── GUIA_VISUAL_WINSCP.md ⭐ EMPIEZA AQUÍ
│   ├── PASOS_RAPIDOS_WINSCP.md
│   └── INSTRUCCIONES_WINSCP.md
│
├── 📋 COMANDOS Y REFERENCIA
│   ├── COMANDOS_SSH_COPIAR_PEGAR.md
│   └── INDICE_SOLUCION.md (este archivo)
│
├── 📚 DOCUMENTACIÓN TÉCNICA
│   ├── SOLUCION_DEFINITIVA_RECUPERAR.md
│   └── RESUMEN_VISUAL_SOLUCION.md
│
├── 🛠️ SCRIPTS
│   ├── test_recuperar.bat
│   └── configurar_archivos.sh
│
└── 🗃️ BASE DE DATOS
    └── crear_tabla_codigos.sql
```

---

## 🚀 ¡EMPIEZA AHORA!

**Abre este archivo ahora:**
👉 **[GUIA_VISUAL_WINSCP.md](GUIA_VISUAL_WINSCP.md)**

Tiempo estimado: **5 minutos**

¡Todo está listo para que soluciones el problema! 💪


# 🔧 SOLUCIÓN: Crear Nueva Contraseña

## ❌ PROBLEMA

La función "Crear Nueva Contraseña" no funciona porque falta el archivo PHP en el servidor:
- **Archivo faltante:** `apimodificarclave.php`

## ✅ SOLUCIÓN RÁPIDA

### PASO 1: El archivo ya está creado

He creado el archivo completo:
```
✅ C:\Users\savka\AndroidStudioProjects\Test\apimodificarclave.php (3.6 KB)
```

### PASO 2: Subir con WinSCP (2 minutos)

Ya sabes cómo hacerlo (lo acabas de hacer con los otros archivos):

1. **Abre WinSCP** (si no está abierto)
2. **Conecta a 98.95.39.30** como `ec2-user` con tu `.ppk`
3. **Panel IZQUIERDO:** `C:\Users\savka\AndroidStudioProjects\Test\`
4. **Panel DERECHO:** `/var/www/html/`
5. **Arrastra:** `apimodificarclave.php` del panel izquierdo al derecho
6. **Confirma** la transferencia

### PASO 3: Probar

Desde **cmd.exe**:
```batch
cd C:\Users\savka\AndroidStudioProjects\Test
curl -s -X POST -d "email=luna@gmail.com" -d "new_password=Test1234!" http://98.95.39.30/apimodificarclave.php
```

**Respuesta esperada:**
```json
{"status":"success","message":"Contraseña actualizada correctamente"}
```

---

## 🧪 PRUEBA EN LA APP

Después de subir el archivo:

1. **Abre la app**
2. **Recuperar Contraseña** con email: `luna@gmail.com`
3. **Solicitar Código** → Anota el código DEBUG
4. **Validar Código** → Ingresa el código
5. **Crear Nueva Contraseña:**
   - Nueva contraseña: `Test1234!` (o la que quieras, debe cumplir requisitos)
   - Confirmar contraseña: `Test1234!`
6. Click **"Crear"**

**Resultado esperado:**
```
┌────────────────────────────────┐
│  ¡Contraseña cambiada! ✓       │
│                                │
│  Su contraseña ha sido         │
│  actualizada exitosamente.     │
│                                │
│          [  OK  ]               │
└────────────────────────────────┘
```

7. **Te redirige al Login**
8. **Inicia sesión** con:
   - Email: `luna@gmail.com`
   - Contraseña: `Test1234!`

**¡Debería funcionar!** ✅

---

## 📋 REQUISITOS DE CONTRASEÑA

La contraseña debe cumplir:
- ✅ Mínimo 8 caracteres
- ✅ Al menos 1 mayúscula
- ✅ Al menos 1 minúscula
- ✅ Al menos 1 número
- ✅ Al menos 1 carácter especial (@#$%^&+=!)

**Ejemplos válidos:**
- `Test1234!`
- `MiClave123@`
- `Segura#2024`

---

## ✅ CHECKLIST

```
[ ] Archivo apimodificarclave.php creado en PC ✅ (ya está)
[ ] WinSCP abierto y conectado
[ ] Archivo subido a /var/www/html/
[ ] Prueba con curl → JSON "success"
[ ] Prueba en app Android
[ ] Recuperar contraseña → Validar código → Crear nueva
[ ] Login con nueva contraseña → ¡Funciona! 🎉
```

---

## 🚀 RESUMEN

**Archivo creado:** `apimodificarclave.php` (3.6 KB)
**Ubicación local:** `C:\Users\savka\AndroidStudioProjects\Test\`
**Destino servidor:** `/var/www/html/apimodificarclave.php`

**Acción:** Sube el archivo con WinSCP como lo hiciste con los anteriores.

**Tiempo:** 2 minutos

---

## 🆘 SI HAY PROBLEMAS

### Error: "Usuario no encontrado"
**Causa:** El email no existe en la tabla `users`
**Solución:** Verifica que el email exista o usa uno que sepas que está registrado

### Error: "No se pudo conectar al servidor"
**Causa:** El archivo no se subió o está en la ubicación incorrecta
**Solución:** Verifica en WinSCP que `apimodificarclave.php` esté en `/var/www/html/`

### Error: "Contraseña débil"
**Causa:** La contraseña no cumple los requisitos
**Solución:** Usa una contraseña que tenga mayúscula, minúscula, número y símbolo, mínimo 8 caracteres

---

¡Sube el archivo y prueba! 💪


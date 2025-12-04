# 📱 GUÍA RÁPIDA: Usar App del Celular como ADMIN

## 🎯 OBJETIVO
Entrar a la app HomePass IoT desde tu celular con permisos de ADMINISTRADOR.

---

## ⚡ PASOS RÁPIDOS

### 1️⃣ VERIFICAR SI YA TIENES USUARIO

**Ejecuta en tu base de datos:**
```sql
SELECT 
    email, 
    rol, 
    estado,
    CONCAT(nombre, ' ', apellido) as nombre_completo
FROM usuarios 
WHERE email = 'savka.carvajal@inacapmail.cl';
```

**Resultado posible:**

#### ✅ **CASO A: Usuario existe y es ADMIN**
```
email: savka.carvajal@inacapmail.cl
rol: ADMINISTRADOR
estado: ACTIVO
```
→ **Solución:** Ve directo al PASO 2 (Login en la app)

#### ⚠️ **CASO B: Usuario existe pero NO es ADMIN**
```
rol: OPERADOR
```
→ **Solución:** Ejecuta esto:
```sql
UPDATE usuarios 
SET rol = 'ADMINISTRADOR' 
WHERE email = 'savka.carvajal@inacapmail.cl';
```
Luego ve al PASO 2

#### ❌ **CASO C: Usuario NO existe**
```
(0 rows)
```
→ **Solución:** Ve al PASO 1B

---

### 1️⃣B SI NO TIENES USUARIO: Registrarte desde la App

1. **Abre la app** HomePass IoT en tu celular
2. En la pantalla de Login, presiona **"¿No tienes cuenta? Regístrate"**
3. **Llena el formulario:**
   ```
   📝 Nombres:      Savka
   📝 Apellidos:    Carvajal
   📧 Email:        savka.carvajal@inacapmail.cl
   🔒 Contraseña:   Test1234!
   🔒 Confirmar:    Test1234!
   🏢 RUT:          12345678-9 (opcional)
   📞 Teléfono:     +56912345678 (opcional)
   🏠 Departamento: 101
   ```

4. **Presiona REGISTRAR**

5. **Resultado esperado:**
   ```
   ✅ ¡Registro exitoso!
   ✅ Automáticamente eres ADMINISTRADOR
      (porque eres el primer usuario del depto 101)
   ```

---

### 2️⃣ LOGIN EN LA APP COMO ADMIN

1. **Abre la app** (o vuelve al Login si acabas de registrarte)

2. **Ingresa tus credenciales:**
   ```
   📧 Email:      savka.carvajal@inacapmail.cl
   🔒 Contraseña: Test1234!
   ```

3. **Presiona INGRESAR** 🚀

4. **Resultado esperado:**
   ```
   ✅ Entra al menú principal
   ✅ Aparecen las opciones:
      - 👥 Gestión de Usuarios (solo ADMIN)
      - 📊 Sensores
      - 👨‍💻 Desarrollador
   ```

---

### 3️⃣ PROBAR FUNCIONALIDADES DE ADMIN

#### Ver Usuarios
1. En el menú, presiona **"Gestión de Usuarios"**
2. Verás lista de todos los usuarios
3. Puedes:
   - 🔍 Buscar usuarios
   - ➕ Agregar nuevo usuario
   - ✏️ Modificar usuario existente
   - 🗑️ Eliminar usuario

#### Agregar Usuario
1. Presiona el botón **➕ (FAB)**
2. Llena los datos del nuevo usuario
3. El sistema asignará automáticamente:
   - **ADMINISTRADOR** si es el primer usuario del departamento
   - **OPERADOR** si ya hay otro usuario en ese departamento

---

## 🐛 PROBLEMAS COMUNES

### ❌ "Email ya registrado"
**Causa:** Ya existe un usuario con ese email.

**Solución 1:** Usa ese email para login (si recuerdas la contraseña)

**Solución 2:** Recupera la contraseña:
1. En Login → "¿Olvidaste tu contraseña?"
2. Ingresa tu email
3. Recibirás código de 5 dígitos
4. Ingresa el código
5. Crea nueva contraseña

**Solución 3:** Elimina el usuario antiguo (desde BD):
```sql
DELETE FROM usuarios WHERE email = 'savka.carvajal@inacapmail.cl';
```

---

### ❌ "Contraseña incorrecta"
**Solución:** Recuperar contraseña desde la app:

1. **En Login → "¿Olvidaste tu contraseña?"**
2. **Ingresa:** savka.carvajal@inacapmail.cl
3. **El sistema enviará un código** (si el email está configurado)
4. **Ver el código** (opción A o B):

   **Opción A - Desde el email:**
   - Revisa tu correo: savka.carvajal@inacapmail.cl
   - Busca email de "HomePass IoT"
   - Copia el código de 5 dígitos

   **Opción B - Desde la base de datos:**
   ```sql
   SELECT code, created_at
   FROM password_resets
   WHERE email = 'savka.carvajal@inacapmail.cl'
   ORDER BY created_at DESC
   LIMIT 1;
   ```

5. **Ingresa el código en la app**
6. **Crea nueva contraseña:** Test1234! (o la que prefieras)
7. **Login con la nueva contraseña** ✅

---

### ❌ "No se puede conectar al servidor"
**Causa:** URL del servidor incorrecta o servidor caído.

**Solución:**
1. **Verifica que el servidor esté funcionando:**
   ```
   http://44.199.155.199/index.php
   ```
   Debería mostrar: "HomePass IoT API funcionando"

2. **Verifica la URL en la app:**
   - Archivo: `Constants.kt`
   - Debe tener: `const val BASE_URL = "http://44.199.155.199"`

---

### ❌ "Usuario inactivo"
**Causa:** El usuario existe pero está desactivado.

**Solución:**
```sql
UPDATE usuarios 
SET estado = 'ACTIVO' 
WHERE email = 'savka.carvajal@inacapmail.cl';
```

---

### ❌ No aparece opción "Gestión de Usuarios"
**Causa:** El usuario NO es ADMINISTRADOR.

**Solución:**
```sql
UPDATE usuarios 
SET rol = 'ADMINISTRADOR' 
WHERE email = 'savka.carvajal@inacapmail.cl';
```

Luego cierra la app y vuelve a hacer login.

---

## 📊 VERIFICAR DATOS DEL USUARIO

**Ejecuta en MySQL:**
```sql
SELECT 
    id_usuario,
    CONCAT(nombre, ' ', apellido) as nombre_completo,
    email,
    rol,
    estado,
    id_departamento,
    fecha_creacion,
    CASE 
        WHEN rol = 'ADMINISTRADOR' THEN '✅ Admin - Puede gestionar usuarios'
        ELSE '⚠️ Operador - Solo lectura'
    END as permisos
FROM usuarios
WHERE email = 'savka.carvajal@inacapmail.cl';
```

**Resultado esperado:**
```
id_usuario:       1
nombre_completo:  Savka Carvajal
email:            savka.carvajal@inacapmail.cl
rol:              ADMINISTRADOR
estado:           ACTIVO
id_departamento:  1
fecha_creacion:   2025-12-03
permisos:         ✅ Admin - Puede gestionar usuarios
```

---

## 🎯 RESUMEN - LO QUE NECESITAS

### Para usar la app como ADMIN:

1. ✅ **Usuario registrado** con email: `savka.carvajal@inacapmail.cl`
2. ✅ **Rol:** `ADMINISTRADOR`
3. ✅ **Estado:** `ACTIVO`
4. ✅ **Contraseña:** La que configuraste al registrarte
5. ✅ **App instalada** en tu celular
6. ✅ **Conexión a internet** para comunicarse con el servidor

---

## 📝 DATOS DE PRUEBA SUGERIDOS

```
📧 Email:         savka.carvajal@inacapmail.cl
🔒 Contraseña:    Test1234!
👤 Nombre:        Savka
👤 Apellido:      Carvajal
📱 Teléfono:      +56912345678
🆔 RUT:           12345678-9
🏠 Departamento:  101
🎭 Rol:           ADMINISTRADOR (automático)
```

---

## 🚀 PRÓXIMOS PASOS

Una vez que entres como ADMIN:

1. **Explora las funcionalidades**
   - Ver lista de usuarios
   - Agregar usuarios
   - Modificar usuarios
   - Eliminar usuarios

2. **Crear más usuarios de prueba**
   - Usuario 2: dante.gutierrez@inacapmail.cl (será OPERADOR)
   - Usuario 3: test@example.com (será OPERADOR)

3. **Probar la app completa**
   - Sensores IoT
   - Gestión de acceso
   - Panel de control

---

## 📞 COMANDOS ÚTILES

### Ver todos los usuarios:
```sql
SELECT email, rol, estado FROM usuarios;
```

### Ver usuarios por departamento:
```sql
SELECT 
    d.numero as depto,
    u.email,
    u.rol,
    COUNT(*) OVER (PARTITION BY u.id_departamento) as usuarios_en_depto
FROM usuarios u
JOIN departamentos d ON u.id_departamento = d.id_departamento
ORDER BY d.numero, u.fecha_creacion;
```

### Promover usuario a ADMIN:
```sql
UPDATE usuarios SET rol = 'ADMINISTRADOR' WHERE email = 'tu_email';
```

### Degradar ADMIN a OPERADOR:
```sql
UPDATE usuarios SET rol = 'OPERADOR' WHERE email = 'tu_email';
```

---

<div align="center">

## ✅ ¡LISTO PARA USAR!

**Ahora puedes usar la app HomePass IoT como ADMINISTRADOR**

</div>

---

**Desarrollado por:** Savka Carvajal & Dante Gutierrez  
**Proyecto:** HomePass IoT - INACAP 2025  
**Fecha:** 3 de diciembre de 2025


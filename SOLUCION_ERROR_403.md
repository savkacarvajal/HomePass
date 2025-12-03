# 🚨 SOLUCIÓN: Error 403 - Permission Denied

## 🔍 El Problema

```
remote: Permission to savkacarvajal/HomePass.git denied to PandaAkiraNakai.
fatal: unable to access 'https://github.com/savkacarvajal/HomePass.git/': error 403
```

### Causas posibles:
1. ❌ Windows tiene guardadas credenciales de otro usuario (`PandaAkiraNakai`)
2. ❌ El repositorio `HomePass` no existe en GitHub
3. ❌ No tienes permisos en el repositorio

---

## ✅ SOLUCIÓN PASO A PASO

### PASO 1: Verificar si el repositorio existe

**Ejecuta:**
```
VERIFICAR_REPOSITORIO.bat
```

Esto abrirá tu perfil de GitHub. Busca el repositorio **"HomePass"**.

#### Si NO existe el repositorio:
1. Clic en el botón verde **"New"** (arriba a la derecha)
2. **Repository name:** `HomePass`
3. **Description:** `App de gestión de contraseñas para hogar inteligente`
4. Selecciona **Public** o **Private** (tu eliges)
5. ⚠️ **NO marques** ninguna de estas opciones:
   - [ ] Add a README file
   - [ ] Add .gitignore  
   - [ ] Choose a license
6. Clic en **"Create repository"**
7. GitHub te mostrará instrucciones - **ignóralas**, ya las tenemos aquí

#### Si SÍ existe el repositorio:
- Verifica que seas el propietario
- Verifica que tu usuario tenga permisos de escritura

---

### PASO 2: Limpiar credenciales antiguas

**Ejecuta:**
```
LIMPIAR_CREDENCIALES.bat
```

Esto eliminará las credenciales de `PandaAkiraNakai` y configurará tu usuario correcto.

---

### PASO 3: Subir el código

**Ejecuta:**
```
SUBIR_A_GITHUB_SEGURO.bat
```

Cuando te pida credenciales:
- **Username:** `savkacarvajal`
- **Password:** Tu token de GitHub (genera uno nuevo)

#### ¿Cómo generar el token?
1. Ve a: https://github.com/settings/tokens
2. Clic en **"Generate new token (classic)"**
3. **Note:** `HomePass - Token 2024`
4. **Expiration:** 90 days
5. **Selecciona permisos:**
   - ✅ `repo` (todos los sub-permisos)
6. Clic en **"Generate token"**
7. **COPIAR** el token (empieza con `ghp_...`)
8. Pegarlo cuando el script te lo pida

---

## 🔧 Solución Alternativa: Comando Manual

Si los scripts no funcionan, usa estos comandos en PowerShell:

```powershell
# 1. Ir al directorio del proyecto
cd "C:\Users\savka\AndroidStudioProjects\HomePass 1.0"

# 2. Limpiar credenciales
cmdkey /delete:LegacyGeneric:target=git:https://github.com

# 3. Configurar usuario
git config user.name "savkacarvajal"
git config user.email "savka.carvajal@inacapmail.cl"

# 4. Verificar remoto
git remote -v

# 5. Si el remoto está mal, corregirlo
git remote set-url origin https://github.com/savkacarvajal/HomePass.git

# 6. Añadir archivos
git add .

# 7. Hacer commit
git commit -m "Initial commit - HomePass app mejorada"

# 8. Push (te pedirá usuario y token)
git push -u origin main
```

---

## 🆘 Otros Errores Comunes

### Error: "repository not found"
**Solución:** El repositorio no existe en GitHub.
- Ve a: https://github.com/savkacarvajal
- Crea el repositorio "HomePass" como se explicó arriba

### Error: "Authentication failed"
**Solución:** Token incorrecto o expirado.
- Genera un nuevo token con permisos `repo`
- Copia TODO el token (empieza con `ghp_`)
- Pégalo cuando te lo pida (no se verá al escribir)

### Error: "Updates were rejected" ⚠️ ESTE ES TU ERROR ACTUAL
**Solución:** El repositorio remoto tiene commits que no tienes local (GitHub creó un README automáticamente).

**OPCIÓN A - Usar el script automático (MÁS FÁCIL):**
```
Ejecuta: SINCRONIZAR_Y_SUBIR.bat
```
Este script hará todo automáticamente.

**OPCIÓN B - Comandos manuales:**
```bash
# 1. Obtener los cambios remotos
git fetch origin

# 2. Integrar con rebase (mantiene historial limpio)
git pull origin main --rebase --allow-unrelated-histories

# 3. Si hay conflictos, resolverlos y continuar:
git rebase --continue

# 4. Hacer push
git push -u origin main
```

**OPCIÓN C - Forzar push (⚠️ CUIDADO - Solo si es tu primer push):**
```bash
git push -u origin main --force
```
⚠️ Solo usa esta opción si estás seguro de que no hay nada importante en GitHub.

### Error: "Could not resolve host"
**Solución:** Problema de conexión a internet.
- Verifica tu conexión
- Intenta: `ping github.com`

---

## ✅ Verificación Final

Una vez que el push sea exitoso:

1. **Ve a:** https://github.com/savkacarvajal/HomePass
2. **Deberías ver:**
   - ✅ Todos los archivos del proyecto
   - ✅ Carpeta `app/` con el código Android
   - ✅ Archivos `.md` de documentación
   - ✅ Tu commit más reciente

3. **Verifica el commit:**
   - Clic en "X commits" arriba
   - Debe aparecer tu commit reciente

---

## 📋 Checklist de Solución

- [ ] Repositorio existe en GitHub
- [ ] Credenciales antiguas eliminadas
- [ ] Usuario Git configurado como `savkacarvajal`
- [ ] Token nuevo generado
- [ ] Push exitoso
- [ ] Código visible en GitHub

---

## 🎯 Archivos Útiles

| Archivo | Para Qué |
|---------|----------|
| `VERIFICAR_REPOSITORIO.bat` | Abrir GitHub y verificar repositorio |
| `LIMPIAR_CREDENCIALES.bat` | Eliminar credenciales antiguas |
| `SUBIR_A_GITHUB_SEGURO.bat` | Hacer push del código |

---

## 💡 Consejo

Si sigues teniendo problemas, considera usar **GitHub Desktop**:

1. Descargar: https://desktop.github.com/
2. Instalar y loguearte con `savkacarvajal`
3. File → Add Local Repository
4. Seleccionar la carpeta del proyecto
5. Hacer commit y push desde la interfaz gráfica

---

**¿Necesitas más ayuda?** Ejecuta los scripts en orden:
1. `VERIFICAR_REPOSITORIO.bat`
2. `LIMPIAR_CREDENCIALES.bat`
3. `SUBIR_A_GITHUB_SEGURO.bat`


# 🚀 Pasos para Subir el Proyecto a GitHub - GUÍA COMPLETA

## ⚠️ Situación Actual

El push anterior falló porque GitHub detectó un token de acceso personal en el historial. Esto es **bueno** - GitHub protege tus credenciales automáticamente.

## ✅ Ya Está Solucionado

- ✅ Token eliminado de .git/config
- ✅ Historial local limpio
- ✅ Archivos nuevos listos para subir:
  - `PLAN_MEJORAS_HOMEPASS.md` (22 mejoras propuestas)
  - `CONFIGURACION_SEGURA.md` (guía de seguridad)
  - `SUBIR_A_GITHUB_SEGURO.bat` (script automático)

## 🔑 PASO 1: Generar Nuevo Token de GitHub

### ¿Por qué necesitas un nuevo token?
El token anterior quedó expuesto en el historial de Git y debe ser revocado por seguridad.

### Cómo generar un nuevo token:

1. **Ir a GitHub Settings**
   - Abre: https://github.com/settings/tokens
   - O: GitHub → Tu foto de perfil → Settings → Developer settings → Personal access tokens → Tokens (classic)

2. **Revocar el token anterior (IMPORTANTE)**
   - Busca el token anterior en la lista
   - Haz clic en "Delete" o "Revoke"

3. **Generar nuevo token**
   - Clic en "Generate new token" → "Generate new token (classic)"
   - **Note:** "HomePass - Token de desarrollo 2024"
   - **Expiration:** 90 days (o el tiempo que prefieras)
   - **Seleccionar permisos:**
     - ✅ `repo` (todos los sub-permisos)
     - ✅ `workflow` (si usarás GitHub Actions)
   - Clic en "Generate token"

4. **COPIAR EL TOKEN INMEDIATAMENTE**
   - GitHub solo lo mostrará UNA VEZ
   - Se verá como: `ghp_xxxxxxxxxxxxxxxxxxxxxxxxxxxx`
   - Guárdalo temporalmente en el Bloc de notas

## 📤 PASO 2: Subir el Código a GitHub

### Opción A: Usando el Script Automático (Más Fácil)

1. **Ejecutar el script**
   ```
   Doble clic en: SUBIR_A_GITHUB_SEGURO.bat
   ```

2. **Cuando te pida credenciales:**
   - **Username:** `savkacarvajal`
   - **Password:** Pega el token que acabas de generar (Ctrl+V)

3. **¡Listo!** El código se subirá a GitHub

### Opción B: Usando Comandos Manuales

Abre PowerShell en la carpeta del proyecto y ejecuta:

```powershell
# 1. Ver estado
git status

# 2. Añadir archivos
git add CONFIGURACION_SEGURA.md PLAN_MEJORAS_HOMEPASS.md SUBIR_A_GITHUB_SEGURO.bat

# 3. Hacer commit
git commit -m "Agregar documentacion de mejoras y configuracion segura"

# 4. Push a GitHub
git push -u origin main
```

Cuando te pida credenciales:
- **Username:** `savkacarvajal`
- **Password:** Tu nuevo token

### Opción C: GitHub Desktop (Si lo tienes instalado)

1. Abre GitHub Desktop
2. Abre el repositorio: File → Add Local Repository → Selecciona la carpeta
3. Verás los cambios en la izquierda
4. Escribe un commit message: "Agregar documentación de mejoras"
5. Clic en "Commit to main"
6. Clic en "Push origin"

## 🔒 PASO 3: Guardar el Token de Forma Segura

### Windows Credential Manager (Recomendado)

Una vez que hagas el primer push exitoso, Windows guardará automáticamente tus credenciales en el Credential Manager.

Para verificar:
1. Windows → Buscar "Credential Manager" o "Administrador de credenciales"
2. Ir a "Windows Credentials" o "Credenciales de Windows"
3. Buscar `git:https://github.com`
4. Ahí estará guardado tu token

### Ventajas:
- ✅ No tendrás que poner el token cada vez
- ✅ El token no está en archivos de texto
- ✅ Es seguro y encriptado por Windows

## 📊 PASO 4: Verificar en GitHub

1. **Abre tu repositorio:**
   https://github.com/savkacarvajal/HomePass

2. **Deberías ver:**
   - ✅ Todos los archivos del proyecto
   - ✅ `PLAN_MEJORAS_HOMEPASS.md` con las 22 mejoras
   - ✅ `CONFIGURACION_SEGURA.md` con la guía de seguridad
   - ✅ El código de la app Android completo

3. **Verificar commits:**
   - Clic en "X commits" en la parte superior
   - Deberías ver tu historial de commits

## 🚨 Solución de Problemas

### Error: "Authentication failed"
**Solución:** El token es incorrecto o expiró.
- Genera un nuevo token
- Asegúrate de copiar TODO el token
- Verifica que tenga permisos `repo`

### Error: "Remote origin already exists"
**Solución:** Ya está configurado, solo haz push:
```bash
git push -u origin main
```

### Error: "Permission denied"
**Solución:** 
- Verifica que el repositorio existe en GitHub
- Asegúrate de ser el propietario del repositorio
- Genera un nuevo token con permisos `repo`

### Error: "Repository not found"
**Solución:**
- Verifica la URL: `https://github.com/savkacarvajal/HomePass.git`
- Asegúrate de que el repositorio existe
- Verifica que tu usuario sea correcto

### Error: "Push declined due to secret scanning"
**Solución:** Ya fue resuelto. El token fue eliminado del historial.

## ✨ PASO 5: Después del Push Exitoso

### Inmediatamente:
1. ✅ Verificar que todo esté en GitHub
2. ✅ Eliminar el token del Bloc de notas (si lo guardaste ahí)
3. ✅ Confirmar que Windows Credential Manager tiene el token guardado

### Recomendaciones:
1. **Crear rama de desarrollo:**
   ```bash
   git checkout -b desarrollo
   git push -u origin desarrollo
   ```

2. **Trabajar en ramas:**
   - `main` → Código estable/producción
   - `desarrollo` → Código en desarrollo
   - `feature/nombre` → Nuevas funcionalidades

3. **Hacer commits frecuentes:**
   ```bash
   git add .
   git commit -m "Descripción clara del cambio"
   git push
   ```

## 🎯 Próximos Pasos en el Proyecto

Una vez que el código esté en GitHub:

### Esta Semana:
1. 🔲 Decidir qué mejoras implementar primero
2. 🔲 Crear rama `mejoras-v2`
3. 🔲 Empezar con seguridad (JWT, HTTPS)

### Referencia:
Consulta `PLAN_MEJORAS_HOMEPASS.md` para el plan completo de 22 mejoras organizadas por categorías.

## 📞 Resumen de URLs Importantes

- **Repositorio:** https://github.com/savkacarvajal/HomePass
- **Generar Token:** https://github.com/settings/tokens
- **Tu Perfil:** https://github.com/savkacarvajal
- **Servidor AWS:** 44.199.155.199

## ✅ Checklist Final

Antes de continuar con las mejoras, asegúrate de:

- [ ] Nuevo token generado
- [ ] Token anterior revocado
- [ ] Push exitoso a GitHub
- [ ] Código visible en https://github.com/savkacarvajal/HomePass
- [ ] Token guardado en Credential Manager
- [ ] Archivos sensibles en .gitignore

---

## 🎉 ¡Todo Listo!

Una vez completados estos pasos, el proyecto estará completamente configurado en GitHub y podremos empezar a implementar las mejoras del `PLAN_MEJORAS_HOMEPASS.md`.

**¿Necesitas ayuda en algún paso específico?**

---

**Última actualización:** 2 de diciembre de 2024


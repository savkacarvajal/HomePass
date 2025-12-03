# 🚀 Cómo Crear y Subir tu Proyecto a GitHub

## ✅ Paso 1: Ya Completado
Tu commit inicial ya está hecho localmente con 132 archivos. ✔️

---

## 📋 Paso 2: Crear el Repositorio en GitHub (HAZLO AHORA)

### Opción A: Desde la Web de GitHub (Recomendado)

1. **Ve a GitHub**: https://github.com

2. **Inicia sesión** con tu cuenta

3. **Crea un nuevo repositorio**:
   - Haz clic en el botón **"+"** (arriba derecha) → **"New repository"**
   - O ve directamente a: https://github.com/new

4. **Configura el repositorio**:
   ```
   Repository name: HomePasss
   Description: Aplicación Android de gestión de usuarios con autenticación
   Visibility: ✅ Public (o Private si prefieres)
   
   ⚠️ NO MARQUES NINGUNA DE ESTAS OPCIONES:
   ❌ Add a README file
   ❌ Add .gitignore
   ❌ Choose a license
   ```

5. **Haz clic en "Create repository"**

---

## 📡 Paso 3: Conectar tu Proyecto Local con GitHub

Después de crear el repositorio, GitHub te mostrará una página con instrucciones. 

**Copia la URL del repositorio** que aparecerá como:
```
https://github.com/TU_USUARIO/HomePass.git
```

Luego ejecuta estos comandos (reemplaza `TU_USUARIO` con tu nombre de usuario de GitHub):

```powershell
cd "C:\Users\savka\AndroidStudioProjects\HomePass 1.0"

# Configurar el remote (reemplaza TU_USUARIO con tu usuario de GitHub)
git remote add origin https://github.com/TU_USUARIO/HomePass.git

# Verificar que se agregó correctamente
git remote -v

# Subir el código
git branch -M main
git push -u origin main
```

---

## 🔑 Autenticación en GitHub

Cuando ejecutes `git push`, GitHub te pedirá autenticación:

### Método 1: GitHub CLI (Recomendado)
Si tienes GitHub CLI instalado:
```powershell
gh auth login
```

### Método 2: Personal Access Token
1. Ve a: https://github.com/settings/tokens
2. Genera un nuevo token (classic)
3. Selecciona scope: `repo`
4. Copia el token
5. Úsalo como contraseña cuando hagas push

### Método 3: GitHub Desktop
Descarga e instala: https://desktop.github.com/
Luego clona tu repositorio desde GitHub Desktop.

---

## 📝 Comandos Rápidos de Referencia

### Ver estado del repositorio
```powershell
git status
```

### Ver el historial de commits
```powershell
git log --oneline
```

### Ver los repositorios remotos configurados
```powershell
git remote -v
```

### Hacer cambios futuros
```powershell
git add .
git commit -m "Descripción del cambio"
git push
```

---

## ⚡ Script Automático

Ejecuta este script después de crear el repositorio en GitHub (reemplaza TU_USUARIO):

```powershell
cd "C:\Users\savka\AndroidStudioProjects\HomePass 1.0"
git remote add origin https://github.com/TU_USUARIO/HomePass.git
git branch -M main
git push -u origin main
```

---

## 🎯 Próximos Pasos

1. ✅ Crear el repositorio en GitHub (Paso 2)
2. ✅ Copiar la URL del repositorio
3. ✅ Ejecutar los comandos del Paso 3
4. ✅ Verificar que el código esté en GitHub

---

## 📊 Información del Proyecto

**Nombre del Proyecto:** HomePasss  
**Archivos Commiteados:** 132 archivos  
**Líneas de Código:** 5753 inserciones  
**IP del Servidor:** 44.199.155.199  
**Tecnologías:** Kotlin, Android, PHP, MySQL

---

## 🆘 Solución de Problemas

### Error: "remote origin already exists"
```powershell
git remote remove origin
git remote add origin https://github.com/TU_USUARIO/HomePass.git
```

### Error: "authentication failed"
Genera un Personal Access Token en GitHub y úsalo como contraseña.

### Ver si el remote está configurado
```powershell
git remote -v
```

---

**¡Listo! Tu proyecto estará en GitHub después de completar estos pasos! 🎉**


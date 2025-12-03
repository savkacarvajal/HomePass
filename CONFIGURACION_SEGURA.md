# 🔐 Configuración Segura del Repositorio

## ⚠️ IMPORTANTE: Información Sensible

**NUNCA incluyas en Git:**
- ❌ Tokens de acceso personal
- ❌ Contraseñas de bases de datos
- ❌ Claves API
- ❌ Certificados privados
- ❌ Archivos .env con credenciales

## 📝 Configuración Actual

### Servidor AWS
- **IP Pública:** 44.199.155.199
- **IP Privada:** 172.31.78.62

### Repositorio GitHub
- **URL:** https://github.com/savkacarvajal/HomePass.git
- **Usuario:** savkacarvajal
- **Email:** savka.carvajal@inacapmail.cl

### Autenticación
Para autenticarte con GitHub, usa **GitHub CLI** o **Personal Access Token** guardado en Windows Credential Manager.

**No incluyas el token directamente en los archivos del proyecto.**

## 🛡️ Archivos Protegidos (.gitignore)

El archivo `.gitignore` ya incluye:
```
# Archivos sensibles
*.env
*.key
*.pem
local.properties

# Configuración IDE
.idea/
.gradle/
```

## 🔑 Cómo Autenticarte de Forma Segura

### Opción 1: GitHub CLI (Recomendado)
```bash
# Instalar GitHub CLI
winget install --id GitHub.cli

# Autenticarse
gh auth login
```

### Opción 2: Personal Access Token
1. Crea un token en: https://github.com/settings/tokens
2. Guárdalo en Windows Credential Manager
3. Git lo usará automáticamente

### Opción 3: SSH
```bash
# Generar clave SSH
ssh-keygen -t ed25519 -C "savka.carvajal@inacapmail.cl"

# Añadir a GitHub
# Copiar el contenido de ~/.ssh/id_ed25519.pub
# Ir a: https://github.com/settings/keys

# Cambiar remote a SSH
git remote set-url origin git@github.com:savkacarvajal/HomePass.git
```

## ✅ Estado Actual

- ✅ Token eliminado del historial Git
- ✅ Configuración limpia en .git/config
- ✅ Credential helper configurado
- ✅ Listo para push seguro

## 📤 Comandos para Push

```bash
# Verificar estado
git status

# Añadir cambios
git add .

# Commit
git commit -m "Descripción del cambio"

# Push (te pedirá credenciales la primera vez)
git push -u origin main
```

## 🔄 Próximos Pasos

1. Generar un nuevo Personal Access Token (el anterior quedó expuesto)
2. Guardarlo en Windows Credential Manager
3. Hacer push del código
4. Implementar las mejoras del PLAN_MEJORAS_HOMEPASS.md


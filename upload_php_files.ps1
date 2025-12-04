# Script para subir archivos PHP a EC2
# HomePass IoT - Nueva Instancia

$EC2_IP = "44.199.155.199"
$EC2_USER = "ec2-user"
$PEM_FILE = "$env:USERPROFILE\Downloads\homepass.pem"
$REMOTE_PATH = "/var/www/html/"
$LOCAL_PATH = "C:\Users\savka\AndroidStudioProjects\HomePass 1.0\"

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  SUBIR ARCHIVOS PHP A EC2" -ForegroundColor Cyan
Write-Host "  IP: $EC2_IP" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

# Verificar que existe el archivo PEM
if (-Not (Test-Path $PEM_FILE)) {
    Write-Host "[ERROR] No se encontró el archivo PEM en: $PEM_FILE" -ForegroundColor Red
    Write-Host "Por favor, asegúrate de que homepass.pem esté en la carpeta Downloads" -ForegroundColor Yellow
    exit 1
}

# Lista de archivos PHP a subir
$archivos = @(
    "conexion.php",
    "apiconsultausu.php",
    "register.php",
    "apimodificarclave.php",
    "solicitar_codigo.php",
    "validar_codigo.php",
    "api_sensores.php",
    "api_eventos.php",
    "api_barrera.php",
    "validar_sensor_rfid.php",
    "get_users.php",
    "update_user.php",
    "delete_user.php",
    "index.php"
)

Write-Host "[INFO] Archivos a subir:" -ForegroundColor Yellow
$archivos | ForEach-Object { Write-Host "  - $_" -ForegroundColor White }
Write-Host ""

# Subir cada archivo
$exitosos = 0
$fallidos = 0

foreach ($archivo in $archivos) {
    $localFile = Join-Path $LOCAL_PATH $archivo

    if (Test-Path $localFile) {
        Write-Host "[SUBIENDO] $archivo..." -ForegroundColor Cyan -NoNewline

        # Comando SCP
        $scpCommand = "scp -i `"$PEM_FILE`" -o StrictHostKeyChecking=no `"$localFile`" ${EC2_USER}@${EC2_IP}:${REMOTE_PATH}"

        try {
            $result = Invoke-Expression $scpCommand 2>&1
            if ($LASTEXITCODE -eq 0) {
                Write-Host " [OK]" -ForegroundColor Green
                $exitosos++
            } else {
                Write-Host " [FALLO]" -ForegroundColor Red
                Write-Host "  Error: $result" -ForegroundColor Red
                $fallidos++
            }
        } catch {
            Write-Host " [ERROR]" -ForegroundColor Red
            Write-Host "  Excepción: $_" -ForegroundColor Red
            $fallidos++
        }
    } else {
        Write-Host "[SKIP] $archivo (no encontrado)" -ForegroundColor Yellow
        $fallidos++
    }
}

Write-Host ""
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  RESUMEN" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "Archivos subidos exitosamente: $exitosos" -ForegroundColor Green
Write-Host "Archivos fallidos: $fallidos" -ForegroundColor $(if ($fallidos -eq 0) { "Green" } else { "Red" })
Write-Host ""

# Establecer permisos en el servidor
Write-Host "[INFO] Estableciendo permisos en el servidor..." -ForegroundColor Cyan
$sshCommand = "ssh -i `"$PEM_FILE`" -o StrictHostKeyChecking=no ${EC2_USER}@${EC2_IP} 'sudo chmod 644 ${REMOTE_PATH}*.php && sudo chown apache:apache ${REMOTE_PATH}*.php'"

try {
    Invoke-Expression $sshCommand
    if ($LASTEXITCODE -eq 0) {
        Write-Host "[OK] Permisos establecidos correctamente" -ForegroundColor Green
    } else {
        Write-Host "[ADVERTENCIA] Error al establecer permisos" -ForegroundColor Yellow
    }
} catch {
    Write-Host "[ADVERTENCIA] No se pudieron establecer los permisos: $_" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "[INFO] Proceso completado!" -ForegroundColor Green
Write-Host "Puedes probar la app ahora: http://$EC2_IP" -ForegroundColor Cyan
Write-Host ""

# Pause para ver resultados
Read-Host "Presiona Enter para salir"


# Script para subir archivos PHP al servidor EC2
# Asegúrate de tener la clave PEM en la ubicación correcta

$EC2_IP = "44.199.155.199"
$PEM_FILE = "C:\Users\savka\Downloads\HomePass.ppk"
$LOCAL_PATH = "C:\Users\savka\AndroidStudioProjects\HomePass 1.0"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  SUBIENDO ARCHIVOS PHP AL SERVIDOR EC2" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Lista de archivos PHP a subir a /var/www/html/
$php_files = @(
    "conexion.php",
    "index.php",
    "apiconsultausu.php",
    "register.php",
    "apimodificarclave.php",
    "solicitar_codigo.php",
    "validar_codigo.php",
    "get_users.php",
    "update_user.php",
    "delete_user.php",
    "api_sensores.php",
    "api_eventos.php",
    "api_barrera.php",
    "validar_sensor_rfid.php",
    "email_config.php"
)

Write-Host "Verificando conexión con el servidor..." -ForegroundColor Yellow
Write-Host ""

# Nota: Para usar SCP con PuTTY (.ppk), necesitas pscp.exe
# Alternativa: Convertir .ppk a .pem con PuTTYgen

Write-Host "INSTRUCCIONES:" -ForegroundColor Green
Write-Host "===============" -ForegroundColor Green
Write-Host ""
Write-Host "1. Abre WinSCP y conecta a:" -ForegroundColor White
Write-Host "   - Host: $EC2_IP" -ForegroundColor Cyan
Write-Host "   - Usuario: ec2-user" -ForegroundColor Cyan
Write-Host "   - Puerto: 22" -ForegroundColor Cyan
Write-Host "   - Clave privada: $PEM_FILE" -ForegroundColor Cyan
Write-Host ""
Write-Host "2. Navega a: /var/www/html" -ForegroundColor White
Write-Host ""
Write-Host "3. Sube los siguientes archivos:" -ForegroundColor White
foreach ($file in $php_files) {
    Write-Host "   - $file" -ForegroundColor Yellow
}
Write-Host ""
Write-Host "4. Cambia permisos en la consola EC2:" -ForegroundColor White
Write-Host "   sudo chown apache:apache /var/www/html/*.php" -ForegroundColor Cyan
Write-Host "   sudo chmod 644 /var/www/html/*.php" -ForegroundColor Cyan
Write-Host ""
Write-Host "5. Ejecuta el setup de base de datos:" -ForegroundColor White
Write-Host "   http://$EC2_IP/setup_database.php" -ForegroundColor Cyan
Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "Presiona cualquier tecla para continuar..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")


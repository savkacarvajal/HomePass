/*
 * ========================================
 * HomePass IoT - Código NodeMCU ESP8266
 * Sistema de Control de Acceso RFID
 * ========================================
 *
 * Componentes:
 * - NodeMCU ESP8266
 * - Módulo RFID RC522
 * - Servo Motor SG90
 * - Aro LED de 12 LEDs (NeoPixel)
 *
 * Funcionalidad:
 * 1. Lee tarjeta/llavero RFID
 * 2. Envía código a API para validación
 * 3. Enciende LED según respuesta
 * 4. Controla servo motor (barrera)
 * 5. Cierre automático después de 10 segundos
 */

#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ArduinoJson.h>
#include <SPI.h>
#include <MFRC522.h>
#include <Servo.h>
#include <Adafruit_NeoPixel.h>

// ========================================
// CONFIGURACIÓN WIFI
// ========================================
const char* ssid = "TU_WIFI";              // ← CAMBIAR
const char* password = "TU_PASSWORD_WIFI"; // ← CAMBIAR

// ========================================
// CONFIGURACIÓN SERVIDOR API
// ========================================
const char* serverUrl = "http://44.199.155.199/validar_sensor_rfid.php";

// ========================================
// PINES DEL HARDWARE
// ========================================
#define RST_PIN         D3      // Pin reset RFID
#define SS_PIN          D4      // Pin SS RFID
#define SERVO_PIN       D1      // Pin Servo Motor
#define LED_PIN         D2      // Pin Aro LED
#define LED_COUNT       12      // Cantidad de LEDs

// ========================================
// OBJETOS
// ========================================
MFRC522 rfid(SS_PIN, RST_PIN);
Servo servoMotor;
Adafruit_NeoPixel leds(LED_COUNT, LED_PIN, NEO_GRB + NEO_KHZ800);
WiFiClient client;

// ========================================
// VARIABLES GLOBALES
// ========================================
unsigned long tiempoApertura = 0;
bool barreraAbierta = false;
const int TIEMPO_APERTURA = 10000; // 10 segundos

// Posiciones del servo
const int SERVO_CERRADO = 0;
const int SERVO_ABIERTO = 90;

// ========================================
// FUNCIONES DE LED
// ========================================

void ledApagado() {
  leds.clear();
  leds.show();
}

void ledVerde() {
  for(int i = 0; i < LED_COUNT; i++) {
    leds.setPixelColor(i, leds.Color(0, 255, 0)); // Verde
  }
  leds.show();
}

void ledRojo() {
  for(int i = 0; i < LED_COUNT; i++) {
    leds.setPixelColor(i, leds.Color(255, 0, 0)); // Rojo
  }
  leds.show();
}

void ledAzul() {
  for(int i = 0; i < LED_COUNT; i++) {
    leds.setPixelColor(i, leds.Color(0, 0, 255)); // Azul
  }
  leds.show();
}

void ledAmarillo() {
  for(int i = 0; i < LED_COUNT; i++) {
    leds.setPixelColor(i, leds.Color(255, 255, 0)); // Amarillo
  }
  leds.show();
}

// ========================================
// FUNCIONES DE BARRERA
// ========================================

void abrirBarrera() {
  Serial.println("🟢 Abriendo barrera...");
  servoMotor.write(SERVO_ABIERTO);
  barreraAbierta = true;
  tiempoApertura = millis();
}

void cerrarBarrera() {
  Serial.println("🔴 Cerrando barrera...");
  servoMotor.write(SERVO_CERRADO);
  barreraAbierta = false;
  ledApagado();
}

// ========================================
// FUNCIÓN PARA LEER UID DE TARJETA RFID
// ========================================

String leerUID() {
  String uid = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    uid += String(rfid.uid.uidByte[i] < 0x10 ? "0" : "");
    uid += String(rfid.uid.uidByte[i], HEX);
  }
  uid.toUpperCase();
  return uid;
}

// ========================================
// FUNCIÓN PARA VALIDAR SENSOR EN API
// ========================================

bool validarSensorAPI(String codigoSensor) {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("❌ WiFi desconectado");
    return false;
  }

  HTTPClient http;
  http.begin(client, serverUrl);
  http.addHeader("Content-Type", "application/json");

  // Crear JSON con el código del sensor
  String jsonPayload = "{\"codigo_sensor\":\"" + codigoSensor + "\"}";

  Serial.println("📡 Enviando a API: " + jsonPayload);

  int httpCode = http.POST(jsonPayload);

  if (httpCode > 0) {
    String response = http.getString();
    Serial.println("📥 Respuesta API: " + response);

    // Parsear JSON
    DynamicJsonDocument doc(1024);
    deserializeJson(doc, response);

    bool success = doc["success"];
    String resultado = doc["resultado"];
    String color = doc["color"];
    bool abrirBarrera = doc["abrir_barrera"];
    String usuario = doc["usuario"] | "Desconocido";
    String departamento = doc["departamento"] | "";

    if (success && resultado == "PERMITIDO") {
      Serial.println("✅ ACCESO PERMITIDO");
      Serial.println("👤 Usuario: " + usuario);
      Serial.println("🏢 Departamento: " + departamento);

      ledVerde();
      abrirBarrera();

      http.end();
      return true;
    } else {
      Serial.println("❌ ACCESO DENEGADO");
      Serial.println("📝 Motivo: " + doc["mensaje"].as<String>());

      ledRojo();
      delay(3000);
      ledApagado();

      http.end();
      return false;
    }
  } else {
    Serial.println("❌ Error HTTP: " + String(httpCode));
    ledRojo();
    delay(2000);
    ledApagado();
  }

  http.end();
  return false;
}

// ========================================
// SETUP
// ========================================

void setup() {
  Serial.begin(115200);
  Serial.println("\n\n========================================");
  Serial.println("🏠 HomePass IoT - Sistema de Control");
  Serial.println("========================================\n");

  // Inicializar LED
  leds.begin();
  ledAzul(); // Azul = Iniciando
  Serial.println("💡 LED inicializado");

  // Inicializar Servo
  servoMotor.attach(SERVO_PIN);
  cerrarBarrera();
  Serial.println("🚪 Servo motor inicializado");

  // Inicializar RFID
  SPI.begin();
  rfid.PCD_Init();
  Serial.println("📡 Módulo RFID inicializado");
  Serial.print("Versión: 0x");
  Serial.println(rfid.PCD_ReadRegister(rfid.VersionReg), HEX);

  // Conectar WiFi
  Serial.println("\n📶 Conectando a WiFi...");
  WiFi.begin(ssid, password);

  ledAmarillo(); // Amarillo = Conectando WiFi

  int intentos = 0;
  while (WiFi.status() != WL_CONNECTED && intentos < 20) {
    delay(500);
    Serial.print(".");
    intentos++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n✅ WiFi conectado!");
    Serial.print("📍 IP: ");
    Serial.println(WiFi.localIP());
    ledApagado();
  } else {
    Serial.println("\n❌ No se pudo conectar a WiFi");
    ledRojo();
    delay(3000);
    ledApagado();
  }

  Serial.println("\n🎯 Sistema listo. Esperando tarjetas RFID...\n");
}

// ========================================
// LOOP PRINCIPAL
// ========================================

void loop() {
  // Verificar cierre automático de barrera
  if (barreraAbierta && (millis() - tiempoApertura >= TIEMPO_APERTURA)) {
    cerrarBarrera();
  }

  // Detectar nueva tarjeta RFID
  if (!rfid.PICC_IsNewCardPresent()) {
    return;
  }

  if (!rfid.PICC_ReadCardSerial()) {
    return;
  }

  // Leer UID de la tarjeta
  String uid = leerUID();
  Serial.println("\n🔍 Tarjeta detectada!");
  Serial.println("🆔 UID: " + uid);

  // Validar en la API
  validarSensorAPI(uid);

  // Detener lectura RFID
  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();

  delay(1000); // Evitar lecturas múltiples
}

// ========================================
// FUNCIONES ADICIONALES
// ========================================

// Función para mostrar información del sistema
void mostrarInfo() {
  Serial.println("\n========================================");
  Serial.println("📊 INFORMACIÓN DEL SISTEMA");
  Serial.println("========================================");
  Serial.print("WiFi: ");
  Serial.println(WiFi.status() == WL_CONNECTED ? "Conectado ✅" : "Desconectado ❌");
  Serial.print("IP: ");
  Serial.println(WiFi.localIP());
  Serial.print("Barrera: ");
  Serial.println(barreraAbierta ? "ABIERTA 🟢" : "CERRADA 🔴");
  Serial.print("Tiempo activo: ");
  Serial.print(millis() / 1000);
  Serial.println(" segundos");
  Serial.println("========================================\n");
}

// ========================================
// NOTAS IMPORTANTES
// ========================================

/*
 * CONEXIONES HARDWARE:
 *
 * RFID RC522:
 * - SDA/SS -> D4
 * - SCK    -> D5
 * - MOSI   -> D7
 * - MISO   -> D6
 * - IRQ    -> (no conectar)
 * - GND    -> GND
 * - RST    -> D3
 * - 3.3V   -> 3.3V
 *
 * Servo Motor:
 * - Señal  -> D1
 * - VCC    -> 5V
 * - GND    -> GND
 *
 * Aro LED (NeoPixel):
 * - DIN    -> D2
 * - VCC    -> 5V
 * - GND    -> GND
 *
 * LIBRERÍAS NECESARIAS:
 * - ESP8266WiFi (incluida en ESP8266)
 * - ESP8266HTTPClient (incluida en ESP8266)
 * - ArduinoJson (instalar desde Library Manager)
 * - MFRC522 (instalar desde Library Manager)
 * - Servo (incluida en Arduino)
 * - Adafruit_NeoPixel (instalar desde Library Manager)
 *
 * CONFIGURACIÓN ARDUINO IDE:
 * - Placa: NodeMCU 1.0 (ESP-12E Module)
 * - Upload Speed: 115200
 * - CPU Frequency: 80 MHz
 * - Flash Size: 4M (3M SPIFFS)
 *
 * CÓDIGOS DE PRUEBA (usar en test_apis.html):
 * - A1B2C3D4 - Llavero Savka (PERMITIDO)
 * - E5F6G7H8 - Tarjeta Savka (PERMITIDO)
 * - I9J0K1L2 - Llavero Dante (PERMITIDO)
 * - M3N4O5P6 - Tarjeta Admin 102 (PERMITIDO)
 */


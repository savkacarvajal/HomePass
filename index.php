<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePass IoT - Sistema de Control de Acceso</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 800px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 60px;
            margin-bottom: 20px;
        }

        h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            font-size: 18px;
        }

        .status {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .status-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            border: 2px solid #e9ecef;
        }

        .status-card.online {
            border-color: #28a745;
            background: #d4edda;
        }

        .status-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .status-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .status-value {
            color: #666;
            font-size: 14px;
        }

        .apis {
            margin-top: 30px;
        }

        .apis h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .api-list {
            display: grid;
            gap: 10px;
        }

        .api-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .api-name {
            font-weight: 600;
            color: #333;
        }

        .api-url {
            color: #666;
            font-size: 14px;
            font-family: monospace;
        }

        .test-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .test-btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #666;
        }

        .version {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏠🔐</div>
            <h1>HomePass IoT</h1>
            <p class="subtitle">Sistema de Control de Acceso Inteligente</p>
        </div>

        <div class="status">
            <div class="status-card online">
                <div class="status-icon">✅</div>
                <div class="status-title">Servidor Web</div>
                <div class="status-value">Apache Online</div>
            </div>

            <div class="status-card online">
                <div class="status-icon">🗄️</div>
                <div class="status-title">Base de Datos</div>
                <div class="status-value" id="db-status">Verificando...</div>
            </div>

            <div class="status-card online">
                <div class="status-icon">📱</div>
                <div class="status-title">App Android</div>
                <div class="status-value">Conectada</div>
            </div>

            <div class="status-card">
                <div class="status-icon">🔌</div>
                <div class="status-title">NodeMCU/Arduino</div>
                <div class="status-value">Pendiente</div>
            </div>
        </div>

        <div class="apis">
            <h2>📡 APIs Disponibles</h2>
            <div class="api-list">
                <div class="api-item">
                    <div>
                        <div class="api-name">🔐 Autenticación</div>
                        <div class="api-url">apiconsultausu.php</div>
                    </div>
                    <button class="test-btn" onclick="testAPI('apiconsultausu.php')">Probar</button>
                </div>

                <div class="api-item">
                    <div>
                        <div class="api-name">📟 Gestión de Sensores</div>
                        <div class="api-url">api_sensores.php</div>
                    </div>
                    <button class="test-btn" onclick="testAPI('api_sensores.php')">Probar</button>
                </div>

                <div class="api-item">
                    <div>
                        <div class="api-name">🚧 Control de Barrera</div>
                        <div class="api-url">api_barrera.php</div>
                    </div>
                    <button class="test-btn" onclick="testAPI('api_barrera.php')">Probar</button>
                </div>

                <div class="api-item">
                    <div>
                        <div class="api-name">📊 Historial de Eventos</div>
                        <div class="api-url">api_eventos.php</div>
                    </div>
                    <button class="test-btn" onclick="testAPI('api_eventos.php')">Probar</button>
                </div>

                <div class="api-item">
                    <div>
                        <div class="api-name">🔍 Validar RFID</div>
                        <div class="api-url">validar_sensor_rfid.php</div>
                    </div>
                    <button class="test-btn" onclick="testAPI('validar_sensor_rfid.php?codigo=TEST')">Probar</button>
                </div>

                <div class="api-item">
                    <div>
                        <div class="api-name">👥 Gestión de Usuarios</div>
                        <div class="api-url">get_users.php</div>
                    </div>
                    <button class="test-btn" onclick="testAPI('get_users.php')">Probar</button>
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>🎓 Proyecto IoT</strong></p>
            <p>Aplicaciones Móviles para IoT - INACAP 2025</p>
            <p>Desarrollado por: Savka Carvajal & Dante Gutierrez</p>
            <span class="version">v2.0 - AWS EC2</span>
        </div>
    </div>

    <script>
        // Verificar conexión a base de datos al cargar
        fetch('test_mysql.php')
            .then(response => response.json())
            .then(data => {
                const statusEl = document.getElementById('db-status');
                const dbCard = statusEl.closest('.status-card');

                if (data.configuraciones_probadas && data.configuraciones_probadas[0].status.includes('✅')) {
                    statusEl.textContent = 'MySQL Online';
                    dbCard.classList.add('online');
                } else {
                    statusEl.textContent = 'Error: ' + (data.configuraciones_probadas[0]?.error || 'Verificar contraseña');
                    statusEl.style.fontSize = '12px';
                    statusEl.style.color = '#d32f2f';
                }
            })
            .catch(error => {
                const statusEl = document.getElementById('db-status');
                statusEl.textContent = 'Error: No se pudo conectar';
                statusEl.style.fontSize = '12px';
                statusEl.style.color = '#d32f2f';
            });

        function testAPI(endpoint) {
            window.open(endpoint, '_blank');
        }
    </script>
</body>
</html>


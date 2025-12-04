package com.example.test

import android.content.Context
import android.content.Intent
import android.graphics.Color
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import com.example.test.databinding.ActivityMainBinding
import com.example.test.models.*
import com.google.gson.Gson
import okhttp3.*
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.RequestBody.Companion.toRequestBody
import java.io.IOException
import java.text.SimpleDateFormat
import java.util.*

class MainActivity : AppCompatActivity() {

    private lateinit var binding: ActivityMainBinding
    private val client = OkHttpClient()
    private val gson = Gson()

    private var userId = 0
    private var departamentoId = 0
    private var userRol = ""
    private var userName = ""

    private val handler = Handler(Looper.getMainLooper())
    private val dateTimeRunnable = object : Runnable {
        override fun run() {
            updateDateTime()
            handler.postDelayed(this, 1000)
        }
    }

    private val estadoBarreraRunnable = object : Runnable {
        override fun run() {
            cargarEstadoBarrera()
            handler.postDelayed(this, 3000) // Actualizar cada 3 segundos
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        cargarDatosUsuario()
        configurarVistas()
        setupClickListeners()
        cargarEstadoBarrera()
    }

    private fun cargarDatosUsuario() {
        val prefs = getSharedPreferences(Constants.Prefs.PREFS_NAME, Context.MODE_PRIVATE)
        userId = prefs.getInt(Constants.Prefs.KEY_USER_ID, 0)
        departamentoId = prefs.getInt(Constants.Prefs.KEY_DEPARTAMENTO_ID, 1)
        userRol = prefs.getString(Constants.Prefs.KEY_USER_ROL, Constants.Roles.OPERADOR) ?: Constants.Roles.OPERADOR
        userName = prefs.getString(Constants.Prefs.KEY_USER_NAME, "Usuario") ?: "Usuario"

        // Mostrar información del usuario
        binding.tvBienvenida?.text = "Bienvenido, $userName"
        binding.tvRolUsuario?.text = "Rol: $userRol"
    }

    private fun configurarVistas() {
        // Configurar visibilidad según rol
        if (userRol == Constants.Roles.ADMINISTRADOR) {
            binding.cardGestionUsuarios?.visibility = View.VISIBLE
            binding.btnCerrarBarrera?.visibility = View.VISIBLE
        } else {
            binding.cardGestionUsuarios?.visibility = View.GONE
            binding.btnCerrarBarrera?.visibility = View.GONE
        }

        // Ocultar card de sensores (temperatura/ampolleta) - no va en esta evaluación
        binding.cardSensores?.visibility = View.GONE
    }

    private fun setupClickListeners() {
        // Navegación existente
        binding.cardGestionUsuarios?.setOnClickListener {
            startActivity(Intent(this, GestionUsuarioActivity::class.java))
        }

        binding.cardSensores?.setOnClickListener {
            startActivity(Intent(this, SensoresActivity::class.java))
        }

        binding.cardDesarrollador?.setOnClickListener {
            startActivity(Intent(this, DesarrolladorActivity::class.java))
        }

        // Nuevas pantallas IoT
        binding.cardGestionSensores?.setOnClickListener {
            startActivity(Intent(this, GestionSensoresActivity::class.java))
        }

        binding.cardHistorialEventos?.setOnClickListener {
            startActivity(Intent(this, HistorialEventosActivity::class.java))
        }

        // Control de barrera
        binding.btnAbrirBarrera?.setOnClickListener {
            mostrarConfirmacionAbrirBarrera()
        }

        binding.btnCerrarBarrera?.setOnClickListener {
            mostrarConfirmacionCerrarBarrera()
        }
    }

    private fun cargarEstadoBarrera() {
        val request = Request.Builder()
            .url(Constants.API.BARRERA_ESTADO)
            .get()
            .build()

        client.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                // No hacer nada si falla
            }

            override fun onResponse(call: Call, response: Response) {
                val responseBody = response.body?.string()
                runOnUiThread {
                    try {
                        val estadoResponse = gson.fromJson(responseBody, EstadoBarreraResponse::class.java)
                        if (estadoResponse.success) {
                            actualizarUIEstadoBarrera(estadoResponse.estadoActual ?: "CERRADA")
                        }
                    } catch (e: Exception) {
                        // No hacer nada si falla
                    }
                }
            }
        })
    }

    private fun actualizarUIEstadoBarrera(estado: String) {
        binding.tvEstadoBarrera?.text = if (estado == "ABIERTA") "🟢 ABIERTA" else "🔴 CERRADA"
        binding.tvEstadoBarrera?.setTextColor(
            if (estado == "ABIERTA") Color.parseColor("#28a745")
            else Color.parseColor("#dc3545")
        )
    }

    private fun mostrarConfirmacionAbrirBarrera() {
        AlertDialog.Builder(this)
            .setTitle("Abrir Barrera")
            .setMessage("¿Desea abrir la barrera manualmente?")
            .setPositiveButton("Abrir") { _, _ ->
                abrirBarrera()
            }
            .setNegativeButton("Cancelar", null)
            .show()
    }

    private fun abrirBarrera() {
        binding.progressBar?.visibility = View.VISIBLE

        val request = ControlBarreraRequest(userId, departamentoId)
        val json = gson.toJson(request)
        val body = json.toRequestBody("application/json".toMediaType())

        val httpRequest = Request.Builder()
            .url(Constants.API.BARRERA_ABRIR)
            .post(body)
            .build()

        client.newCall(httpRequest).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                runOnUiThread {
                    binding.progressBar?.visibility = View.GONE
                    Toast.makeText(this@MainActivity, "Error de conexión", Toast.LENGTH_SHORT).show()
                }
            }

            override fun onResponse(call: Call, response: Response) {
                val responseBody = response.body?.string()
                runOnUiThread {
                    binding.progressBar?.visibility = View.GONE
                    try {
                        val controlResponse = gson.fromJson(responseBody, ControlBarreraResponse::class.java)
                        if (controlResponse.success) {
                            Toast.makeText(this@MainActivity, "✅ Barrera abierta", Toast.LENGTH_SHORT).show()
                            actualizarUIEstadoBarrera("ABIERTA")
                        } else {
                            Toast.makeText(this@MainActivity, controlResponse.mensaje, Toast.LENGTH_SHORT).show()
                        }
                    } catch (e: Exception) {
                        Toast.makeText(this@MainActivity, "Error al procesar respuesta", Toast.LENGTH_SHORT).show()
                    }
                }
            }
        })
    }

    private fun mostrarConfirmacionCerrarBarrera() {
        if (userRol != Constants.Roles.ADMINISTRADOR) {
            Toast.makeText(this, "Solo administradores pueden cerrar la barrera manualmente", Toast.LENGTH_SHORT).show()
            return
        }

        AlertDialog.Builder(this)
            .setTitle("Cerrar Barrera")
            .setMessage("¿Desea cerrar la barrera manualmente?")
            .setPositiveButton("Cerrar") { _, _ ->
                cerrarBarrera()
            }
            .setNegativeButton("Cancelar", null)
            .show()
    }

    private fun cerrarBarrera() {
        binding.progressBar?.visibility = View.VISIBLE

        val request = ControlBarreraRequest(userId, departamentoId)
        val json = gson.toJson(request)
        val body = json.toRequestBody("application/json".toMediaType())

        val httpRequest = Request.Builder()
            .url(Constants.API.BARRERA_CERRAR)
            .post(body)
            .build()

        client.newCall(httpRequest).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                runOnUiThread {
                    binding.progressBar?.visibility = View.GONE
                    Toast.makeText(this@MainActivity, "Error de conexión", Toast.LENGTH_SHORT).show()
                }
            }

            override fun onResponse(call: Call, response: Response) {
                val responseBody = response.body?.string()
                runOnUiThread {
                    binding.progressBar?.visibility = View.GONE
                    try {
                        val controlResponse = gson.fromJson(responseBody, ControlBarreraResponse::class.java)
                        if (controlResponse.success) {
                            Toast.makeText(this@MainActivity, "✅ Barrera cerrada", Toast.LENGTH_SHORT).show()
                            actualizarUIEstadoBarrera("CERRADA")
                        } else {
                            Toast.makeText(this@MainActivity, controlResponse.mensaje, Toast.LENGTH_SHORT).show()
                        }
                    } catch (e: Exception) {
                        Toast.makeText(this@MainActivity, "Error al procesar respuesta", Toast.LENGTH_SHORT).show()
                    }
                }
            }
        })
    }

    override fun onResume() {
        super.onResume()
        handler.post(dateTimeRunnable)
        handler.post(estadoBarreraRunnable)
    }

    override fun onPause() {
        super.onPause()
        handler.removeCallbacks(dateTimeRunnable)
        handler.removeCallbacks(estadoBarreraRunnable)
    }

    private fun updateDateTime() {
        val sdf = SimpleDateFormat("dd/MM/yyyy HH:mm:ss", Locale.getDefault())
        val currentDate = sdf.format(Date())
        binding.textViewDateTime?.text = currentDate
    }
}
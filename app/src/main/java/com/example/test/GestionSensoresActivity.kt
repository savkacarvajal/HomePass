package com.example.test

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.*
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.test.models.*
import com.google.android.material.floatingactionbutton.FloatingActionButton
import com.google.gson.Gson
import okhttp3.*
import okhttp3.MediaType.Companion.toMediaType
import okhttp3.RequestBody.Companion.toRequestBody
import java.io.IOException

class GestionSensoresActivity : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var adapter: SensoresAdapter
    private lateinit var fabAgregar: FloatingActionButton
    private lateinit var progressBar: ProgressBar
    private lateinit var tvNoData: TextView

    private val sensoresList = mutableListOf<Sensor>()
    private val client = OkHttpClient()
    private val gson = Gson()

    private var userId = 0
    private var departamentoId = 0
    private var userRol = ""

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_gestion_sensores)

        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.title = "Gestión de Sensores"

        // Obtener datos del usuario
        val prefs = getSharedPreferences(Constants.Prefs.PREFS_NAME, Context.MODE_PRIVATE)
        userId = prefs.getInt(Constants.Prefs.KEY_USER_ID, 0)
        departamentoId = prefs.getInt(Constants.Prefs.KEY_DEPARTAMENTO_ID, 1)
        userRol = prefs.getString(Constants.Prefs.KEY_USER_ROL, Constants.Roles.OPERADOR) ?: Constants.Roles.OPERADOR

        initViews()
        setupRecyclerView()
        cargarSensores()
    }

    private fun initViews() {
        recyclerView = findViewById(R.id.recyclerViewSensores)
        fabAgregar = findViewById(R.id.fabAgregarSensor)
        progressBar = findViewById(R.id.progressBar)
        tvNoData = findViewById(R.id.tvNoData)

        // Solo administradores pueden agregar sensores
        if (userRol == Constants.Roles.ADMINISTRADOR) {
            fabAgregar.setOnClickListener {
                mostrarDialogoAgregarSensor()
            }
        } else {
            fabAgregar.visibility = View.GONE
        }
    }

    private fun setupRecyclerView() {
        adapter = SensoresAdapter(sensoresList, userRol) { sensor, action ->
            when (action) {
                "cambiar_estado" -> mostrarDialogoCambiarEstado(sensor)
                "ver_detalles" -> mostrarDetallesSensor(sensor)
            }
        }
        recyclerView.layoutManager = LinearLayoutManager(this)
        recyclerView.adapter = adapter
    }

    private fun cargarSensores() {
        progressBar.visibility = View.VISIBLE
        tvNoData.visibility = View.GONE

        val url = "${Constants.API.SENSORES_POR_DEPARTAMENTO}&id_departamento=$departamentoId"
        val request = Request.Builder()
            .url(url)
            .get()
            .build()

        client.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                runOnUiThread {
                    progressBar.visibility = View.GONE
                    Toast.makeText(this@GestionSensoresActivity, "Error de conexión", Toast.LENGTH_SHORT).show()
                }
            }

            override fun onResponse(call: Call, response: Response) {
                val responseBody = response.body?.string()
                runOnUiThread {
                    progressBar.visibility = View.GONE
                    try {
                        val sensoresResponse = gson.fromJson(responseBody, SensoresResponse::class.java)
                        if (sensoresResponse.success && sensoresResponse.datos != null) {
                            sensoresList.clear()
                            sensoresList.addAll(sensoresResponse.datos)
                            adapter.notifyDataSetChanged()

                            if (sensoresList.isEmpty()) {
                                tvNoData.visibility = View.VISIBLE
                                tvNoData.text = "No hay sensores registrados"
                            } else {
                                tvNoData.visibility = View.GONE
                            }
                        } else {
                            tvNoData.visibility = View.VISIBLE
                            tvNoData.text = sensoresResponse.mensaje ?: "No se encontraron sensores"
                        }
                    } catch (e: Exception) {
                        Toast.makeText(this@GestionSensoresActivity, "Error al procesar datos", Toast.LENGTH_SHORT).show()
                    }
                }
            }
        })
    }

    private fun mostrarDialogoAgregarSensor() {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_agregar_sensor, null)
        val etCodigo = dialogView.findViewById<EditText>(R.id.etCodigoSensor)
        val etNombre = dialogView.findViewById<EditText>(R.id.etNombreSensor)
        val spinnerTipo = dialogView.findViewById<Spinner>(R.id.spinnerTipoSensor)

        val tipos = arrayOf("LLAVERO", "TARJETA")
        spinnerTipo.adapter = ArrayAdapter(this, android.R.layout.simple_spinner_dropdown_item, tipos)

        AlertDialog.Builder(this)
            .setTitle("Agregar Nuevo Sensor")
            .setView(dialogView)
            .setPositiveButton("Agregar") { _, _ ->
                val codigo = etCodigo.text.toString().trim()
                val nombre = etNombre.text.toString().trim()
                val tipo = spinnerTipo.selectedItem.toString()

                if (codigo.isEmpty()) {
                    Toast.makeText(this, "Ingrese el código del sensor", Toast.LENGTH_SHORT).show()
                    return@setPositiveButton
                }

                agregarSensor(codigo, nombre, tipo)
            }
            .setNegativeButton("Cancelar", null)
            .show()
    }

    private fun agregarSensor(codigo: String, nombre: String, tipo: String) {
        progressBar.visibility = View.VISIBLE

        val request = AgregarSensorRequest(
            idDepartamento = departamentoId,
            idUsuario = userId,
            codigoSensor = codigo,
            nombreSensor = nombre,
            tipo = tipo
        )

        val json = gson.toJson(request)
        val body = json.toRequestBody("application/json".toMediaType())

        val httpRequest = Request.Builder()
            .url(Constants.API.SENSORES_AGREGAR)
            .post(body)
            .build()

        client.newCall(httpRequest).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                runOnUiThread {
                    progressBar.visibility = View.GONE
                    Toast.makeText(this@GestionSensoresActivity, "Error de conexión", Toast.LENGTH_SHORT).show()
                }
            }

            override fun onResponse(call: Call, response: Response) {
                val responseBody = response.body?.string()
                runOnUiThread {
                    progressBar.visibility = View.GONE
                    try {
                        val jsonResponse = gson.fromJson(responseBody, SensoresResponse::class.java)
                        if (jsonResponse.success) {
                            Toast.makeText(this@GestionSensoresActivity, "Sensor agregado exitosamente", Toast.LENGTH_SHORT).show()
                            cargarSensores()
                        } else {
                            Toast.makeText(this@GestionSensoresActivity, jsonResponse.mensaje ?: "Error al agregar sensor", Toast.LENGTH_SHORT).show()
                        }
                    } catch (e: Exception) {
                        Toast.makeText(this@GestionSensoresActivity, "Error al procesar respuesta", Toast.LENGTH_SHORT).show()
                    }
                }
            }
        })
    }

    private fun mostrarDialogoCambiarEstado(sensor: Sensor) {
        val estados = arrayOf("ACTIVO", "INACTIVO", "PERDIDO", "BLOQUEADO")

        AlertDialog.Builder(this)
            .setTitle("Cambiar Estado del Sensor")
            .setItems(estados) { _, which ->
                val nuevoEstado = estados[which]
                cambiarEstadoSensor(sensor.idSensor, nuevoEstado)
            }
            .setNegativeButton("Cancelar", null)
            .show()
    }

    private fun cambiarEstadoSensor(idSensor: Int, nuevoEstado: String) {
        progressBar.visibility = View.VISIBLE

        val request = ActualizarEstadoSensorRequest(idSensor, nuevoEstado)
        val json = gson.toJson(request)
        val body = json.toRequestBody("application/json".toMediaType())

        val httpRequest = Request.Builder()
            .url(Constants.API.SENSORES_ACTUALIZAR_ESTADO)
            .put(body)
            .build()

        client.newCall(httpRequest).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                runOnUiThread {
                    progressBar.visibility = View.GONE
                    Toast.makeText(this@GestionSensoresActivity, "Error de conexión", Toast.LENGTH_SHORT).show()
                }
            }

            override fun onResponse(call: Call, response: Response) {
                val responseBody = response.body?.string()
                runOnUiThread {
                    progressBar.visibility = View.GONE
                    try {
                        val jsonResponse = gson.fromJson(responseBody, SensoresResponse::class.java)
                        if (jsonResponse.success) {
                            Toast.makeText(this@GestionSensoresActivity, "Estado actualizado", Toast.LENGTH_SHORT).show()
                            cargarSensores()
                        } else {
                            Toast.makeText(this@GestionSensoresActivity, jsonResponse.mensaje ?: "Error al actualizar", Toast.LENGTH_SHORT).show()
                        }
                    } catch (e: Exception) {
                        Toast.makeText(this@GestionSensoresActivity, "Error al procesar respuesta", Toast.LENGTH_SHORT).show()
                    }
                }
            }
        })
    }

    private fun mostrarDetallesSensor(sensor: Sensor) {
        val mensaje = """
            Código: ${sensor.codigoSensor}
            Nombre: ${sensor.nombreSensor ?: "Sin nombre"}
            Tipo: ${sensor.getIconoTipo()} ${sensor.tipo}
            Estado: ${sensor.estado}
            Usuario: ${sensor.getNombreCompleto()}
            Departamento: ${sensor.getDepartamentoCompleto()}
            Fecha Alta: ${sensor.fechaAlta}
        """.trimIndent()

        AlertDialog.Builder(this)
            .setTitle("Detalles del Sensor")
            .setMessage(mensaje)
            .setPositiveButton("OK", null)
            .show()
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressedDispatcher.onBackPressed()
        return true
    }

    // Adapter para RecyclerView
    class SensoresAdapter(
        private val sensores: List<Sensor>,
        private val userRol: String,
        private val onAction: (Sensor, String) -> Unit
    ) : RecyclerView.Adapter<SensoresAdapter.ViewHolder>() {

        class ViewHolder(view: View) : RecyclerView.ViewHolder(view) {
            val tvNombre: TextView = view.findViewById(R.id.tvNombreSensor)
            val tvCodigo: TextView = view.findViewById(R.id.tvCodigoSensor)
            val tvTipo: TextView = view.findViewById(R.id.tvTipoSensor)
            val tvEstado: TextView = view.findViewById(R.id.tvEstadoSensor)
            val tvUsuario: TextView = view.findViewById(R.id.tvUsuarioSensor)
            val btnCambiarEstado: Button = view.findViewById(R.id.btnCambiarEstado)
            val cardView: View = view.findViewById(R.id.cardSensor)
        }

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val view = LayoutInflater.from(parent.context)
                .inflate(R.layout.item_sensor, parent, false)
            return ViewHolder(view)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            val sensor = sensores[position]

            holder.tvNombre.text = sensor.nombreSensor ?: "Sensor ${sensor.idSensor}"
            holder.tvCodigo.text = "Código: ${sensor.codigoSensor}"
            holder.tvTipo.text = "${sensor.getIconoTipo()} ${sensor.tipo}"
            holder.tvEstado.text = sensor.estado
            holder.tvEstado.setTextColor(sensor.getColorEstado())
            holder.tvUsuario.text = "Usuario: ${sensor.getNombreCompleto()}"

            // Solo administradores pueden cambiar estado
            if (userRol == Constants.Roles.ADMINISTRADOR) {
                holder.btnCambiarEstado.visibility = View.VISIBLE
                holder.btnCambiarEstado.setOnClickListener {
                    onAction(sensor, "cambiar_estado")
                }
            } else {
                holder.btnCambiarEstado.visibility = View.GONE
            }

            holder.cardView.setOnClickListener {
                onAction(sensor, "ver_detalles")
            }
        }

        override fun getItemCount() = sensores.size
    }
}


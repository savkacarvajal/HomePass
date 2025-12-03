package com.example.test

import android.content.Context
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout
import com.example.test.models.*
import com.google.gson.Gson
import okhttp3.*
import java.io.IOException
import java.text.SimpleDateFormat
import java.util.*

class HistorialEventosActivity : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var adapter: EventosAdapter
    private lateinit var progressBar: ProgressBar
    private lateinit var tvNoData: TextView
    private lateinit var swipeRefresh: SwipeRefreshLayout
    private lateinit var tvEstadisticas: TextView

    private val eventosList = mutableListOf<Evento>()
    private val client = OkHttpClient()
    private val gson = Gson()

    private var departamentoId = 0
    private val handler = Handler(Looper.getMainLooper())
    private val autoRefreshRunnable = object : Runnable {
        override fun run() {
            cargarEventos(false)
            handler.postDelayed(this, 5000) // Refrescar cada 5 segundos
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_historial_eventos)

        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.title = "Historial de Eventos"

        // Obtener datos del usuario
        val prefs = getSharedPreferences(Constants.Prefs.PREFS_NAME, Context.MODE_PRIVATE)
        departamentoId = prefs.getInt(Constants.Prefs.KEY_DEPARTAMENTO_ID, 1)

        initViews()
        setupRecyclerView()
        cargarEventos(true)
        cargarEstadisticas()

        // Iniciar actualización automática
        handler.post(autoRefreshRunnable)
    }

    override fun onDestroy() {
        super.onDestroy()
        handler.removeCallbacks(autoRefreshRunnable)
    }

    private fun initViews() {
        recyclerView = findViewById(R.id.recyclerViewEventos)
        progressBar = findViewById(R.id.progressBar)
        tvNoData = findViewById(R.id.tvNoData)
        swipeRefresh = findViewById(R.id.swipeRefresh)
        tvEstadisticas = findViewById(R.id.tvEstadisticas)

        swipeRefresh.setOnRefreshListener {
            cargarEventos(false)
            cargarEstadisticas()
        }
    }

    private fun setupRecyclerView() {
        adapter = EventosAdapter(eventosList)
        recyclerView.layoutManager = LinearLayoutManager(this)
        recyclerView.adapter = adapter
    }

    private fun cargarEventos(showProgress: Boolean) {
        if (showProgress) {
            progressBar.visibility = View.VISIBLE
        }
        tvNoData.visibility = View.GONE

        val url = "${Constants.API.EVENTOS_POR_DEPARTAMENTO}&id_departamento=$departamentoId&limite=50"
        val request = Request.Builder()
            .url(url)
            .get()
            .build()

        client.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                runOnUiThread {
                    progressBar.visibility = View.GONE
                    swipeRefresh.isRefreshing = false
                    if (eventosList.isEmpty()) {
                        tvNoData.visibility = View.VISIBLE
                        tvNoData.text = "Error de conexión"
                    }
                }
            }

            override fun onResponse(call: Call, response: Response) {
                val responseBody = response.body?.string()
                runOnUiThread {
                    progressBar.visibility = View.GONE
                    swipeRefresh.isRefreshing = false

                    try {
                        val eventosResponse = gson.fromJson(responseBody, EventosResponse::class.java)
                        if (eventosResponse.success && eventosResponse.datos != null) {
                            eventosList.clear()
                            eventosList.addAll(eventosResponse.datos)
                            adapter.notifyDataSetChanged()

                            if (eventosList.isEmpty()) {
                                tvNoData.visibility = View.VISIBLE
                                tvNoData.text = "No hay eventos registrados"
                            } else {
                                tvNoData.visibility = View.GONE
                            }
                        } else {
                            if (eventosList.isEmpty()) {
                                tvNoData.visibility = View.VISIBLE
                                tvNoData.text = eventosResponse.mensaje ?: "No se encontraron eventos"
                            }
                        }
                    } catch (e: Exception) {
                        if (eventosList.isEmpty()) {
                            tvNoData.visibility = View.VISIBLE
                            tvNoData.text = "Error al procesar datos"
                        }
                    }
                }
            }
        })
    }

    private fun cargarEstadisticas() {
        val calendar = Calendar.getInstance()
        val formatoFecha = SimpleDateFormat("yyyy-MM-dd", Locale.getDefault())
        val fechaHasta = formatoFecha.format(calendar.time)

        calendar.add(Calendar.DAY_OF_MONTH, -7)
        val fechaDesde = formatoFecha.format(calendar.time)

        val url = "${Constants.API.EVENTOS_ESTADISTICAS}&fecha_desde=$fechaDesde&fecha_hasta=$fechaHasta&id_departamento=$departamentoId"
        val request = Request.Builder()
            .url(url)
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
                        val statsResponse = gson.fromJson(responseBody, EstadisticasResponse::class.java)
                        if (statsResponse.success && statsResponse.estadisticas != null) {
                            val stats = statsResponse.estadisticas
                            val texto = """
                                📊 Últimos 7 días:
                                Total: ${stats.totalEventos} | ✅ ${stats.accesosPermitidos} | ❌ ${stats.accesosDenegados}
                                Tasa de éxito: ${String.format("%.1f", stats.porcentajeExito)}%
                            """.trimIndent()
                            tvEstadisticas.text = texto
                            tvEstadisticas.visibility = View.VISIBLE
                        }
                    } catch (e: Exception) {
                        // No hacer nada si falla
                    }
                }
            }
        })
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressedDispatcher.onBackPressed()
        return true
    }

    // Adapter para RecyclerView
    class EventosAdapter(
        private val eventos: List<Evento>
    ) : RecyclerView.Adapter<EventosAdapter.ViewHolder>() {

        class ViewHolder(view: View) : RecyclerView.ViewHolder(view) {
            val tvTipoEvento: TextView = view.findViewById(R.id.tvTipoEvento)
            val tvResultado: TextView = view.findViewById(R.id.tvResultado)
            val tvUsuario: TextView = view.findViewById(R.id.tvUsuario)
            val tvFechaHora: TextView = view.findViewById(R.id.tvFechaHora)
            val tvSensor: TextView = view.findViewById(R.id.tvSensor)
            val viewIndicador: View = view.findViewById(R.id.viewIndicador)
        }

        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
            val view = LayoutInflater.from(parent.context)
                .inflate(R.layout.item_evento, parent, false)
            return ViewHolder(view)
        }

        override fun onBindViewHolder(holder: ViewHolder, position: Int) {
            val evento = eventos[position]

            holder.tvTipoEvento.text = "${evento.getIconoTipoEvento()} ${evento.getTipoEventoLegible()}"
            holder.tvResultado.text = evento.resultado
            holder.tvResultado.setTextColor(evento.getColorResultado())

            holder.tvUsuario.text = "👤 ${evento.getNombreUsuarioCompleto()}"

            // Formatear fecha
            try {
                val fechaOriginal = SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault()).parse(evento.fechaHora)
                val fechaFormateada = SimpleDateFormat("dd/MM/yyyy HH:mm", Locale.getDefault()).format(fechaOriginal!!)
                holder.tvFechaHora.text = "🕐 $fechaFormateada"
            } catch (e: Exception) {
                holder.tvFechaHora.text = "🕐 ${evento.fechaHora}"
            }

            if (!evento.nombreSensor.isNullOrEmpty()) {
                holder.tvSensor.text = "📟 ${evento.nombreSensor} (${evento.codigoSensor})"
                holder.tvSensor.visibility = View.VISIBLE
            } else if (!evento.codigoSensor.isNullOrEmpty()) {
                holder.tvSensor.text = "📟 ${evento.codigoSensor}"
                holder.tvSensor.visibility = View.VISIBLE
            } else {
                holder.tvSensor.visibility = View.GONE
            }

            holder.viewIndicador.setBackgroundColor(evento.getColorResultado())
        }

        override fun getItemCount() = eventos.size
    }
}


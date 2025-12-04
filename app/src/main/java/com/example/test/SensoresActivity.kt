package com.example.test

import android.os.Bundle
import android.os.Handler
import android.os.Looper
import androidx.appcompat.app.AppCompatActivity
import cn.pedant.SweetAlert.SweetAlertDialog
import com.android.volley.Request
import com.android.volley.RequestQueue
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.example.test.databinding.ActivitySensoresBinding
import org.json.JSONException
import java.text.SimpleDateFormat
import java.util.Calendar
import java.util.Locale

class SensoresActivity : AppCompatActivity() {

    // 2. Declarar Binding y lazy init para Volley
    private lateinit var binding: ActivitySensoresBinding
    private val datos: RequestQueue by lazy { Volley.newRequestQueue(this) }
    private val mHandler = Handler(Looper.getMainLooper())

    private var isLightBulbOn = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        // 3. Inicializar View Binding
        binding = ActivitySensoresBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // Habilitar botón de volver en ActionBar
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.title = "Sensores"

        // Solo botón de ampolleta (eliminado humedad y linterna)
        binding.buttonLightBulb.setOnClickListener {
            toggleLightBulb()
        }
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressedDispatcher.onBackPressed()
        return true
    }

    private fun fechahora(): String {
        val c: Calendar = Calendar.getInstance()
        val sdf = SimpleDateFormat("dd MMMM yyyy, hh:mm:ss a", Locale.getDefault())
        return sdf.format(c.time)
    }

    private fun obtenerDatos() {
        val url = "https://www.pnk.cl/muestra_datos.php" // API de sensores
        val request = JsonObjectRequest(Request.Method.GET, url, null,
            { response ->
                try {
                    val temperatura = response.getString("temperatura")
                    // Solo temperatura, sin humedad
                    binding.txtTemp.text = "$temperatura°C"
                    cambiarImagen(temperatura.toFloat())
                } catch (e: JSONException) {
                    e.printStackTrace()
                }
            },
            { error ->
                error.printStackTrace()
            }
        )
        datos.add(request)
    }

    private fun cambiarImagen(valor: Float) {
        // Requerimiento 11: >20°C (alta) o <=20°C (baja)
        val imageRes = if (valor > 20) {
            R.drawable.ic_temp_high
        } else {
            R.drawable.ic_temp_low
        }
        binding.imagenTemp.setImageResource(imageRes)
    }

    private val refrescar = object : Runnable {
        override fun run() {
            binding.txtFecha.text = fechahora()
            obtenerDatos()
            // Requerimiento 11: Actualizar cada 2 segundos
            mHandler.postDelayed(this, 2000)
        }
    }

    private fun toggleLightBulb() {
        isLightBulbOn = !isLightBulbOn
        val status = if (isLightBulbOn) "encendida" else "apagada"
        val imageRes = if (isLightBulbOn) R.drawable.ic_light_on else R.drawable.ic_light_off

        binding.buttonLightBulb.setIconResource(imageRes)

        SweetAlertDialog(this, SweetAlertDialog.SUCCESS_TYPE)
            .setTitleText("Ampolleta $status")
            .show()
    }

    override fun onPause() {
        super.onPause()
        mHandler.removeCallbacks(refrescar)
    }

    override fun onResume() {
        super.onResume()
        mHandler.post(refrescar)
    }
}
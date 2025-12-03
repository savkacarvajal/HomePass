package com.example.test

import android.content.Context
import android.content.Intent
import android.hardware.camera2.CameraManager
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import androidx.appcompat.app.AppCompatActivity
import cn.pedant.SweetAlert.SweetAlertDialog
import com.android.volley.Request
import com.android.volley.RequestQueue
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
// 1. Importar View Binding (asume que el layout se llama activity_sensores.xml)
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

    private lateinit var cameraManager: CameraManager
    private var isFlashlightOn = false
    private lateinit var cameraId: String

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        // 3. Inicializar View Binding
        binding = ActivitySensoresBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // 4. 🌟 CORRECCIÓN AQUÍ 🌟
        // Usar el ID del MaterialButton (button_light_bulb)
        binding.buttonLightBulb.setOnClickListener {
            toggleLightBulb()
        }

        setupFlashlight()
        // 4. 🌟 CORRECCIÓN AQUÍ 🌟
        // Usar el ID del MaterialButton (button_flashlight)
        binding.buttonFlashlight.setOnClickListener {
            toggleFlashlight()
        }
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
                    val humedad = response.getString("humedad")
                    // 5. Usar binding para actualizar UI (esto estaba bien)
                    binding.txtTemp.text = "$temperatura°C"
                    binding.txtHumedad.text = "$humedad%"
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
        // Requerimiento 11: Alternar icono de ampolleta
        val imageRes = if (isLightBulbOn) R.drawable.ic_light_on else R.drawable.ic_light_off

        // 6. 🌟 CORRECCIÓN AQUÍ 🌟
        // Actualizar el icono del *botón*
        binding.buttonLightBulb.setIconResource(imageRes)

        // Requerimiento 11: Confirmación con SweetAlert
        SweetAlertDialog(this, SweetAlertDialog.SUCCESS_TYPE)
            .setTitleText("Ampolleta $status")
            .show()
    }

    private fun setupFlashlight() {
        // Requerimiento 11: Controlar linterna (hardware)
        cameraManager = getSystemService(Context.CAMERA_SERVICE) as CameraManager
        try {
            cameraId = cameraManager.cameraIdList[0]
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun toggleFlashlight() {
        try {
            isFlashlightOn = !isFlashlightOn
            cameraManager.setTorchMode(cameraId, isFlashlightOn)

            // Usar iconos específicos de linterna
            val iconRes = if (isFlashlightOn) {
                R.drawable.ic_flashlight_on
            } else {
                R.drawable.ic_flashlight_off
            }
            binding.buttonFlashlight.setIconResource(iconRes)

        } catch (e: Exception) {
            e.printStackTrace()
            SweetAlertDialog(this, SweetAlertDialog.ERROR_TYPE)
                .setTitleText("Error de linterna")
                .setContentText("No se pudo controlar la linterna.")
                .show()
        }
    }

    override fun onPause() {
        super.onPause()
        mHandler.removeCallbacks(refrescar)
        if (isFlashlightOn) { // Buena práctica: apagar linterna al salir
            toggleFlashlight()
        }
    }

    override fun onResume() {
        super.onResume()
        mHandler.post(refrescar)
    }
}
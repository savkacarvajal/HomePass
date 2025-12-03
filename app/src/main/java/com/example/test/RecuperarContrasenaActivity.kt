package com.example.test

import android.content.Intent
import android.os.Bundle
import android.util.Patterns
import android.view.View
import androidx.appcompat.app.AppCompatActivity
import cn.pedant.SweetAlert.SweetAlertDialog
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.example.test.databinding.RecuperarcontrasenaBinding
import org.json.JSONObject

class RecuperarContrasenaActivity : AppCompatActivity() {

    private lateinit var binding: RecuperarcontrasenaBinding
    private var progressDialog: SweetAlertDialog? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = RecuperarcontrasenaBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // Botón 1: Solicitar Código
        binding.buttonRecuperar.setOnClickListener {
            val email = binding.editTextTextEmailAddress.text.toString().trim()

            // Requerimiento: Validar email no vacío/formato
            if (email.isBlank() || !Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
                showErrorAlert("Entrada inválida", "Por favor, ingrese un email válido.")
                return@setOnClickListener
            }

            solicitarCodigo(email)
        }

        // Botón 2: Validar Código
        binding.buttonValidar.setOnClickListener {
            val email = binding.editTextTextEmailAddress.text.toString().trim()
            val code = binding.editTextCode.text.toString().trim()

            // Requerimiento: Validar campo código no vacío y sólo números (5 dígitos)
            if (code.isBlank() || !code.matches(Regex("\\d{5}"))) {
                showErrorAlert("Código inválido", "Por favor, ingrese un código numérico de 5 dígitos.")
                return@setOnClickListener
            }

            validarCodigo(email, code)
        }
    }

    // --- Lógica de Red: Solicitud de Código (Paso 1) ---

    private fun solicitarCodigo(email: String) {
        showLoadingDialog("Solicitando Código")
        // Llama al script que guarda el código de 5 dígitos en la BD y simula el envío por correo
        val url = "http://44.199.155.199/solicitar_codigo.php"
        val queue = Volley.newRequestQueue(this)

        val stringRequest = object : StringRequest(Method.POST, url,
            Response.Listener<String> { response ->
                hideLoadingDialog()
                try {
                    // Log para debug - muestra la respuesta completa
                    android.util.Log.d("RecuperarContrasena", "Respuesta del servidor: $response")

                    val jsonResponse = JSONObject(response)
                    val status = jsonResponse.getString("status")
                    val message = jsonResponse.getString("message")

                    if (status == "success") {
                        // Muestra SweetAlert
                        SweetAlertDialog(this, SweetAlertDialog.SUCCESS_TYPE)
                            .setTitleText("¡Correo enviado!")
                            .setContentText(message) // Muestra el código de simulación
                            .setConfirmClickListener {
                                it.dismissWithAnimation()
                                // Mostrar campos ocultos para el código
                                binding.textView5.visibility = View.VISIBLE
                                binding.textFieldCode.visibility = View.VISIBLE
                                binding.buttonValidar.visibility = View.VISIBLE
                            }
                            .show()
                    } else {
                        showErrorAlert("Error al enviar", message)
                    }
                } catch (e: Exception) {
                    android.util.Log.e("RecuperarContrasena", "Error al parsear JSON", e)
                    showErrorAlert("Error de respuesta", "Respuesta del servidor inválida:\n${response.take(200)}\n\nError: ${e.message}")
                }
            },
            Response.ErrorListener { error ->
                hideLoadingDialog()
                val errorMsg = when {
                    error.networkResponse != null -> {
                        val statusCode = error.networkResponse.statusCode
                        val data = error.networkResponse.data?.toString(Charsets.UTF_8) ?: "Sin datos"
                        "Código HTTP: $statusCode\nRespuesta: ${data.take(200)}"
                    }
                    error.message != null -> error.message
                    else -> "Error desconocido al conectar"
                }
                android.util.Log.e("RecuperarContrasena", "Error de red: $errorMsg", error)
                showErrorAlert("Error de conexión", "No se pudo conectar al servidor.\n\n$errorMsg")
            }) {
            override fun getParams(): MutableMap<String, String> {
                val params = HashMap<String, String>()
                params["email"] = email
                return params
            }
        }
        queue.add(stringRequest)
    }

    // --- Lógica de Red: Validación de Código (Paso 2) ---

    private fun validarCodigo(email: String, code: String) {
        showLoadingDialog("Validando Código")
        // Llama al script que compara el código, revisa la caducidad (1 min) y borra el registro
        val url = Constants.API.VALIDAR_CODIGO
        val queue = Volley.newRequestQueue(this)

        val stringRequest = object : StringRequest(Method.POST, url,
            Response.Listener<String> { response ->
                hideLoadingDialog()
                try {
                    // Log para debug - muestra la respuesta completa
                    android.util.Log.d("RecuperarContrasena", "Respuesta validar código: $response")

                    val jsonResponse = JSONObject(response)
                    val status = jsonResponse.getString("status")
                    val message = jsonResponse.getString("message")

                    if (status == "success") {
                        // Redirigir a Crear Contraseña
                        SweetAlertDialog(this, SweetAlertDialog.SUCCESS_TYPE)
                            .setTitleText("¡Código validado!")
                            .setContentText("Ahora puede crear una nueva contraseña.")
                            .setConfirmClickListener {
                                // Pasar el email a la siguiente actividad
                                val intent = Intent(this, CrearContrasenaActivity::class.java)
                                intent.putExtra("USER_EMAIL", email)
                                startActivity(intent)
                                it.dismissWithAnimation()
                                finish()
                            }
                            .show()
                    } else {
                        // Código incorrecto o expirado
                        showErrorAlert("Validación fallida", message)
                    }
                } catch (e: Exception) {
                    android.util.Log.e("RecuperarContrasena", "Error al parsear JSON validación", e)
                    showErrorAlert("Error de respuesta", "Respuesta del servidor inválida:\n${response.take(200)}\n\nError: ${e.message}")
                }
            },
            Response.ErrorListener { error ->
                hideLoadingDialog()
                val errorMsg = when {
                    error.networkResponse != null -> {
                        val statusCode = error.networkResponse.statusCode
                        val data = error.networkResponse.data?.toString(Charsets.UTF_8) ?: "Sin datos"
                        "Código HTTP: $statusCode\nRespuesta: ${data.take(200)}"
                    }
                    error.message != null -> error.message
                    else -> "Error desconocido al conectar"
                }
                android.util.Log.e("RecuperarContrasena", "Error de red validación: $errorMsg", error)
                showErrorAlert("Error de conexión", "No se pudo conectar al servidor.\n\n$errorMsg")
            }) {
            override fun getParams(): MutableMap<String, String> {
                val params = HashMap<String, String>()
                params["email"] = email
                params["code"] = code
                return params
            }
        }
        queue.add(stringRequest)
    }

    // --- Funciones de Utilidad ---

    private fun showLoadingDialog(title: String) {
        if (progressDialog == null) {
            progressDialog = SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE)
                .setTitleText(title)
                .setContentText("Enviando datos...")
            progressDialog?.setCancelable(false)
        }
        progressDialog?.show()
    }

    private fun hideLoadingDialog() {
        progressDialog?.dismissWithAnimation()
    }

    private fun showErrorAlert(title: String, message: String) {
        hideLoadingDialog()
        SweetAlertDialog(this, SweetAlertDialog.ERROR_TYPE)
            .setTitleText(title)
            .setContentText(message)
            .show()
    }
}
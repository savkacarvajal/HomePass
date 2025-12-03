package com.example.test

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import cn.pedant.SweetAlert.SweetAlertDialog
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.example.test.databinding.CrearcontrasenaBinding
import org.json.JSONObject

class CrearContrasenaActivity : AppCompatActivity() {

    private lateinit var binding: CrearcontrasenaBinding
    private var progressDialog: SweetAlertDialog? = null
    private var userEmail: String? = null // Almacena el email del usuario a modificar

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = CrearcontrasenaBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // **PASO CRÍTICO:** Recibir el email del Intent (asumido desde RecuperarContrasenaActivity)
        userEmail = intent.getStringExtra("USER_EMAIL")
        if (userEmail.isNullOrBlank()) {
            showErrorAlert("Error", "No se pudo recuperar la información del usuario para actualizar.")
            // Opcional: Redirigir si el email es nulo
            // startActivity(Intent(this, RecuperarContrasenaActivity::class.java))
            return
        }

        binding.buttonCrear.setOnClickListener {
            val newPassword = binding.editTextNewPassword.text.toString()
            val confirmPassword = binding.editTextConfirmPassword.text.toString()

            // 1. Validación de campos vacíos
            if (newPassword.isBlank() || confirmPassword.isBlank()) {
                showErrorAlert("Campos vacíos", "Por favor, complete todos los campos.")
                return@setOnClickListener
            }
            // 2. Validación de coincidencia de claves
            if (newPassword != confirmPassword) {
                showErrorAlert("Las contraseñas no coinciden", "Por favor, asegúrese de que ambas contraseñas sean idénticas.")
                return@setOnClickListener
            }
            // 3. Validación de robustez (Requerimiento de la pauta)
            if (!isPasswordRobust(newPassword)) {
                showErrorAlert("Contraseña débil", "La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un carácter especial.")
                return@setOnClickListener
            }

            // 4. Iniciar lógica de actualización en el servidor
            actualizarContrasena(userEmail!!, newPassword)
        }
    }

    private fun actualizarContrasena(email: String, nuevaContrasena: String) {
        showLoadingDialog("Actualizando Contraseña")

        // Reemplaza con la URL de tu servicio para modificar clave
        val url = Constants.API.MODIFICAR_CLAVE
        val queue = Volley.newRequestQueue(this)

        val stringRequest = object : StringRequest(Method.POST, url,
            Response.Listener<String> { response ->
                hideLoadingDialog()
                try {
                    val jsonResponse = JSONObject(response)
                    val status = jsonResponse.getString("status")

                    if (status == "success") {
                        // SweetAlert para mensaje de éxito (Requerimiento)
                        SweetAlertDialog(this, SweetAlertDialog.SUCCESS_TYPE)
                            .setTitleText("¡Contraseña cambiada!")
                            .setContentText("Su contraseña ha sido actualizada exitosamente.")
                            .setConfirmClickListener {
                                // Redirige al Login (ActLogin) y limpia el stack de actividades
                                val intent = Intent(this, ActLogin::class.java)
                                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
                                startActivity(intent)
                                it.dismissWithAnimation()
                                finish()
                            }
                            .show()
                    } else {
                        val message = jsonResponse.optString("message", "No se pudo actualizar la contraseña en el servidor.")
                        showErrorAlert("Error al Guardar", message)
                    }
                } catch (e: Exception) {
                    showErrorAlert("Error de Respuesta", "Hubo un problema al procesar la respuesta del servidor.")
                }
            },
            Response.ErrorListener { error ->
                hideLoadingDialog()
                showErrorAlert("Error de Red", "No se pudo conectar al servidor. Error: ${error.message}")
            }) {

            override fun getParams(): MutableMap<String, String> {
                val params = HashMap<String, String>()
                params["email"] = email
                params["new_password"] = nuevaContrasena // Asume que la API espera "new_password"
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

    private fun isPasswordRobust(password: String): Boolean {
        // Requerimiento: >=8 caracteres, 1 mayús, 1 minús, 1 número, 1 carácter especial
        val passwordPattern = "^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!])(?=\\S+$).{8,}$".toRegex()
        return passwordPattern.matches(password)
    }
}
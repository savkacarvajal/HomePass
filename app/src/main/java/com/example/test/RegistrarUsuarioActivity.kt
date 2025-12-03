package com.example.test

import android.content.Intent
import android.os.Bundle
import android.util.Patterns
import androidx.appcompat.app.AppCompatActivity
import cn.pedant.SweetAlert.SweetAlertDialog
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
// Importar View Binding (asume que el layout se llama activity_registrar_usuario.xml)
import com.example.test.databinding.ActivityRegistrarUsuarioBinding
import org.json.JSONObject

class RegistrarUsuarioActivity : AppCompatActivity() {

    // Declarar View Binding y ProgressDialog
    private lateinit var binding: ActivityRegistrarUsuarioBinding
    private var progressDialog: SweetAlertDialog? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        // Inicializar View Binding
        binding = ActivityRegistrarUsuarioBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // Usar binding para el listener
        binding.buttonRegistrar.setOnClickListener {
            // Usar binding para acceder a las vistas
            val nombres = binding.editTextNombres.text.toString().trim()
            val apellidos = binding.editTextApellidos.text.toString().trim()
            val email = binding.editTextEmail.text.toString().trim()
            val password = binding.editTextPassword.text.toString()
            val confirmPassword = binding.editTextConfirmPassword.text.toString()

            // --- Validaciones (Ya están correctas) ---
            if (nombres.isBlank() || apellidos.isBlank() || email.isBlank() || password.isBlank() || confirmPassword.isBlank()) {
                showErrorAlert("Campos obligatorios", "Por favor, complete todos los campos.")
                return@setOnClickListener
            }
            if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
                showErrorAlert("Formato inválido", "Por favor, ingrese un email válido.")
                return@setOnClickListener
            }
            if (password != confirmPassword) {
                showErrorAlert("Contraseñas no coinciden", "Las contraseñas no coinciden.")
                return@setOnClickListener
            }
            if (!isPasswordRobust(password)) {
                showErrorAlert("Contraseña débil", "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.")
                return@setOnClickListener
            }

            // Mostrar carga ANTES de la llamada de red
            showLoadingDialog()
            // El nombre de la variable local 'password' no importa, se pasa a la función
            registrarUsuarioEnServidor(nombres, apellidos, email, password)
        }
    }

    private fun registrarUsuarioEnServidor(nombres: String, apellidos: String, email: String, contrasena: String) {
        // Esta URL ahora funcionará gracias al cambio en AndroidManifest.xml
        val url = "http://44.199.155.199/register.php"
        val queue = Volley.newRequestQueue(this)

        val stringRequest = object : StringRequest(Method.POST, url,
            Response.Listener<String> { response ->
                hideLoadingDialog() // Ocultar carga al recibir respuesta
                try {
                    val jsonResponse = JSONObject(response)
                    val status = jsonResponse.getString("status")
                    val message = jsonResponse.getString("message")

                    if (status == "success") {
                        SweetAlertDialog(this, SweetAlertDialog.SUCCESS_TYPE)
                            .setTitleText("¡Registro exitoso!")
                            .setContentText(message)
                            .setConfirmClickListener {
                                val intent = Intent(this, ActLogin::class.java)
                                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
                                startActivity(intent)
                                it.dismissWithAnimation()
                                finish()
                            }
                            .show()
                    } else {
                        showErrorAlert("Error en el registro", message)
                    }
                } catch (e: Exception) {
                    showErrorAlert("Error", "Hubo un problema al procesar la respuesta del servidor.")
                }
            },
            Response.ErrorListener { error ->
                hideLoadingDialog() // Ocultar carga si hay error
                showErrorAlert("Error de red", "No se pudo conectar al servidor. Error: ${error.message}")
            }) {

            override fun getParams(): MutableMap<String, String> {
                val params = HashMap<String, String>()
                params["nombres"] = nombres
                params["apellidos"] = apellidos
                params["email"] = email

                // 🌟 CAMBIO REALIZADO AQUÍ 🌟
                // Ahora coincide con tu columna 'contrasena' de la BD
                params["contrasena"] = contrasena

                return params
            }
        }

        queue.add(stringRequest)
    }

    // --- Funciones de Utilidad (SweetAlert y Validación) ---

    private fun isPasswordRobust(password: String): Boolean {
        val passwordPattern = "^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!])(?=\\S+$).{8,}$".toRegex()
        return passwordPattern.matches(password)
    }

    private fun showLoadingDialog() {
        if (progressDialog == null) {
            progressDialog = SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE)
                .setTitleText("Registrando")
                .setContentText("Por favor, espere...")
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
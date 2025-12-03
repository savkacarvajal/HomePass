package com.example.test

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Patterns
import androidx.appcompat.app.AppCompatActivity
import cn.pedant.SweetAlert.SweetAlertDialog
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.example.test.databinding.ActLoginBinding
import org.json.JSONObject

class ActLogin : AppCompatActivity() {

    private lateinit var binding: ActLoginBinding
    private var progressDialog: SweetAlertDialog? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActLoginBinding.inflate(layoutInflater)
        setContentView(binding.root)

        setupListeners()
    }

    private fun setupListeners() {
        binding.button2.setOnClickListener {
            val email = binding.editTextText.text.toString().trim()
            val password = binding.editTextTextPassword.text.toString()

            if (email.isBlank() || password.isBlank()) {
                showErrorAlert("Oops...", "Todos los campos son obligatorios.")
                return@setOnClickListener
            }
            if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
                showErrorAlert("Error de formato", "Por favor, ingrese un email válido.")
                return@setOnClickListener
            }

            autenticarUsuario(email, password)
        }

        binding.textView2.setOnClickListener {
            val intent = Intent(this, RegistrarUsuarioActivity::class.java)
            startActivity(intent)
        }

        binding.textView3.setOnClickListener {
            val intent = Intent(this, RecuperarContrasenaActivity::class.java)
            startActivity(intent)
        }
    }

    private fun autenticarUsuario(email: String, contrasena: String) {
        showLoadingDialog()

        val url = Constants.API.LOGIN
        val queue = Volley.newRequestQueue(this)

        val stringRequest = object : StringRequest(Method.POST, url,
            Response.Listener<String> { response ->
                hideLoadingDialog()
                try {
                    val jsonResponse = JSONObject(response)
                    val status = jsonResponse.getString("status")

                    if (status == "success") {
                        // Guardar datos del usuario en SharedPreferences
                        val userData = jsonResponse.optJSONObject("user")
                        if (userData != null) {
                            guardarDatosUsuario(userData)
                        }

                        SweetAlertDialog(this, SweetAlertDialog.SUCCESS_TYPE)
                            .setTitleText("¡Ingreso exitoso!")
                            .setConfirmText("Continuar")
                            .setConfirmClickListener {
                                it.dismissWithAnimation()
                                val intent = Intent(this, MainActivity::class.java)
                                startActivity(intent)
                                finish()
                            }
                            .show()
                    } else {
                        val message = jsonResponse.optString("message", "Credenciales incorrectas o usuario no encontrado.")
                        showErrorAlert("Credenciales inválidas", message)
                    }
                } catch (e: Exception) {
                    showErrorAlert("Error de respuesta", "Hubo un problema al procesar la respuesta del servidor.")
                }
            },
            Response.ErrorListener { error ->
                hideLoadingDialog()

                val errorMessage = when (error.networkResponse?.statusCode) {
                    401 -> "Acceso denegado. Usuario o clave incorrectos."
                    in 400..499 -> "Error del cliente (${error.networkResponse.statusCode})."
                    in 500..599 -> "Error del servidor. Intente más tarde."
                    else -> "No se pudo conectar al servidor. Verifique su conexión a Internet."
                }
                showErrorAlert("Error de red", errorMessage)
            }) {

            override fun getParams(): MutableMap<String, String> {
                val params = HashMap<String, String>()
                params["email"] = email
                params["contrasena"] = contrasena
                return params
            }
        }

        queue.add(stringRequest)
    }

    private fun guardarDatosUsuario(userData: JSONObject) {
        val prefs = getSharedPreferences(Constants.Prefs.PREFS_NAME, Context.MODE_PRIVATE)
        val editor = prefs.edit()

        // Guardar datos del usuario
        editor.putInt(Constants.Prefs.KEY_USER_ID, userData.optInt("id_usuario", 0))
        editor.putString(Constants.Prefs.KEY_USER_EMAIL, userData.optString("email", ""))
        editor.putString(Constants.Prefs.KEY_USER_NAME, userData.optString("nombre", "Usuario"))
        editor.putString(Constants.Prefs.KEY_USER_ROL, userData.optString("rol", Constants.Roles.OPERADOR))
        editor.putInt(Constants.Prefs.KEY_DEPARTAMENTO_ID, userData.optInt("id_departamento", 1))
        editor.putBoolean(Constants.Prefs.KEY_IS_LOGGED_IN, true)

        editor.apply()
    }

    private fun showLoadingDialog() {
        if (progressDialog == null) {
            progressDialog = SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE)
                .setTitleText("Autenticando")
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
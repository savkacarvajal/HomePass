package com.example.test

import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.util.Patterns
import android.view.LayoutInflater
import android.view.View
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import cn.pedant.SweetAlert.SweetAlertDialog
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonArrayRequest
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
// Importar View Bindings (Correcto)
import com.example.test.databinding.ActivityListarUsuariosBinding
import com.example.test.databinding.DialogModificarUsuarioBinding
import org.json.JSONException
import org.json.JSONObject

class ListarUsuariosActivity : AppCompatActivity() {

    private lateinit var binding: ActivityListarUsuariosBinding
    private lateinit var userAdapter: UserAdapter
    private val userList = mutableListOf<User>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityListarUsuariosBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // Habilitar botón de volver
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.title = "Gestión de Usuarios"

        binding.recyclerViewUsers.layoutManager = LinearLayoutManager(this)
        userAdapter = UserAdapter(userList) { user ->
            showEditDeleteDialog(user)
        }
        binding.recyclerViewUsers.adapter = userAdapter

        binding.editTextSearch.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {}
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {
                userAdapter.filter(s.toString())
                checkEmptyState()
            }
            override fun afterTextChanged(s: Editable?) {}
        })
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressedDispatcher.onBackPressed()
        return true
    }

    override fun onResume() {
        super.onResume()
        cargarUsuariosDesdeServidor()
    }

    private fun cargarUsuariosDesdeServidor() {
        val url = "http://98.95.39.30/get_users.php"
        val queue = Volley.newRequestQueue(this)

        val jsonArrayRequest = JsonArrayRequest(Request.Method.GET, url, null,
            { response ->
                try {
                    userList.clear()
                    for (i in 0 until response.length()) {
                        val userObject = response.getJSONObject(i)
                        userList.add(User(
                            id = userObject.getLong("id"),
                            nombres = userObject.getString("nombres"),
                            apellidos = userObject.getString("apellidos"),
                            email = userObject.getString("email")
                        ))
                    }
                    userAdapter.updateUsers(userList)
                    userAdapter.filter(binding.editTextSearch.text.toString())
                    checkEmptyState() // 🌟 (Añadido) Para manejar el estado vacío al cargar
                } catch (e: JSONException) {
                    showErrorAlert("Error de Parseo", "La respuesta del servidor no es válida.")
                }
            },
            { error ->
                showErrorAlert("Error de Red", "No se pudo obtener la lista de usuarios. Error: ${error.message}")
            }
        )
        queue.add(jsonArrayRequest)
    }

    // 🌟 INICIO: FUNCIÓN CORREGIDA PARA COINCIDIR CON EL XML 🌟
    private fun showEditDeleteDialog(user: User) {
        // 1. Inflar el layout del diálogo (que contiene los botones)
        val dialogBinding = DialogModificarUsuarioBinding.inflate(LayoutInflater.from(this))

        // 2. Poblar datos (sin cambios)
        dialogBinding.editTextNombres.setText(user.nombres)
        dialogBinding.editTextApellidos.setText(user.apellidos)
        dialogBinding.editTextEmail.setText(user.email)

        // 3. Crear el diálogo SIN botones por defecto
        val dialog = AlertDialog.Builder(this)
            .setView(dialogBinding.root) // Usar el layout inflado
            .setCancelable(true) // Permitir cerrar tocando fuera
            .create()

        // 4. Asignar listener al botón MODIFICAR (del XML)
        dialogBinding.buttonModificar.setOnClickListener {
            val nuevosNombres = dialogBinding.editTextNombres.text.toString().trim()
            val nuevosApellidos = dialogBinding.editTextApellidos.text.toString().trim()
            val nuevoEmail = dialogBinding.editTextEmail.text.toString().trim()

            // Validaciones (Punto 10 de la pauta)
            val soloLetrasEspacios = "^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$".toRegex() // Añadido soporte para acentos y ñ

            if (nuevosNombres.isBlank() || nuevosApellidos.isBlank() || nuevoEmail.isBlank()) {
                showErrorAlert("Campos vacíos", "Todos los campos son obligatorios.")
                return@setOnClickListener
            }
            if (!nuevosNombres.matches(soloLetrasEspacios) || !nuevosApellidos.matches(soloLetrasEspacios)) {
                showErrorAlert("Datos inválidos", "Nombres y Apellidos solo deben contener letras y espacios.")
                return@setOnClickListener
            }
            if (!Patterns.EMAIL_ADDRESS.matcher(nuevoEmail).matches()) {
                showErrorAlert("Formato inválido", "Por favor, ingrese un email válido.")
                return@setOnClickListener
            }

            updateUserInServer(user.id, nuevosNombres, nuevosApellidos, nuevoEmail, dialog)
        }

        // 5. Asignar listener al botón ELIMINAR (del XML)
        dialogBinding.buttonEliminar.setOnClickListener {
            dialog.dismiss() // Cerrar el diálogo de edición
            showDeleteConfirmationDialog(user) // Abrir el diálogo de confirmación
        }

        dialog.show()
    }
    // 🌟 FIN: FUNCIÓN CORREGIDA 🌟


    private fun updateUserInServer(id: Long, nombres: String, apellidos: String, email: String, dialog: AlertDialog) {
        val url = "http://44.199.155.199/update_user.php"
        val queue = Volley.newRequestQueue(this)

        val request = object : StringRequest(Method.POST, url,
            Response.Listener { response ->
                try {
                    val json = JSONObject(response)
                    if (json.getString("status") == "success") {
                        dialog.dismiss()
                        SweetAlertDialog(this, SweetAlertDialog.SUCCESS_TYPE).setTitleText("¡Éxito!").setContentText("Usuario actualizado correctamente.").show()
                        cargarUsuariosDesdeServidor() // Recargar la lista
                    } else {
                        // Manejar error del servidor (ej. "email duplicado")
                        showErrorAlert("Error", json.getString("message"))
                    }
                } catch (e: JSONException) { showErrorAlert("Error de respuesta", "El servidor envió una respuesta inesperada.") }
            },
            Response.ErrorListener { error -> showErrorAlert("Error de red", "No se pudo conectar al servidor: ${error.message}") }
        ) {
            override fun getParams(): Map<String, String> = mapOf(
                "id" to id.toString(),
                "nombres" to nombres,
                "apellidos" to apellidos,
                "email" to email,
                "contrasena" to "" // 🌟 (Añadido) Se envía vacío para que el PHP no falle si espera 5 params
            )
        }
        queue.add(request)
    }

    private fun showDeleteConfirmationDialog(user: User) {
        // (Punto 10) Confirmación de eliminación
        SweetAlertDialog(this, SweetAlertDialog.WARNING_TYPE)
            .setTitleText("¿Estás seguro?")
            .setContentText("Esta acción no se puede deshacer.")
            .setConfirmText("Sí, eliminar")
            .setConfirmClickListener { sDialog ->
                deleteUserFromServer(user.id, sDialog)
            }
            .setCancelButton("Cancelar") { it.dismissWithAnimation() }
            .show()
    }

    private fun deleteUserFromServer(id: Long, sDialog: SweetAlertDialog) {
        val url = Constants.API.DELETE_USER
        val queue = Volley.newRequestQueue(this)
        val request = object : StringRequest(Method.POST, url,
            Response.Listener {
                sDialog.setTitleText("¡Eliminado!").setContentText("El usuario ha sido eliminado.").changeAlertType(SweetAlertDialog.SUCCESS_TYPE)
                cargarUsuariosDesdeServidor() // Recargar la lista
            },
            Response.ErrorListener {
                sDialog.setTitleText("Error").setContentText("No se pudo eliminar al usuario.").changeAlertType(SweetAlertDialog.ERROR_TYPE)
            }
        ) {
            override fun getParams(): Map<String, String> = mapOf("id" to id.toString())
        }
        queue.add(request)
    }

    private fun showErrorAlert(title: String, message: String) {
        SweetAlertDialog(this, SweetAlertDialog.ERROR_TYPE).setTitleText(title).setContentText(message).show()
    }

    // 🌟 (Añadido) Función para mostrar la animación Lottie si la lista está vacía
    private fun checkEmptyState() {
        // (Asumo que tu XML 'activity_listar_usuarios' tiene un 'lottieEmptyView'
        // y un 'recyclerViewUsers' como te sugerí)
        if (userAdapter.itemCount == 0) {
            binding.recyclerViewUsers.visibility = View.GONE
            // binding.lottieEmptyView.visibility = View.VISIBLE
        } else {
            binding.recyclerViewUsers.visibility = View.VISIBLE
            // binding.lottieEmptyView.visibility = View.GONE
        }
    }
}
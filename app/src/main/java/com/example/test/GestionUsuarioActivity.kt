package com.example.test

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
// 1. Importar View Binding
import com.example.test.databinding.ActivityGestionUsuarioBinding

class GestionUsuarioActivity : AppCompatActivity() {

    // 2. Declarar View Binding
    private lateinit var binding: ActivityGestionUsuarioBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // 3. Inicializar View Binding
        binding = ActivityGestionUsuarioBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // 4. Usar binding para los listeners
        binding.cardIngresarUsuario.setOnClickListener {
            val intent = Intent(this, RegistrarUsuarioActivity::class.java)
            startActivity(intent)
        }

        binding.cardListarUsuarios.setOnClickListener {
            val intent = Intent(this, ListarUsuariosActivity::class.java)
            startActivity(intent)
        }
    }
}
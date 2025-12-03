package com.example.test

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import com.example.test.databinding.ActivityDesarrolladorBinding

class DesarrolladorActivity : AppCompatActivity() {

    private lateinit var binding: ActivityDesarrolladorBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        binding = ActivityDesarrolladorBinding.inflate(layoutInflater)
        setContentView(binding.root)

        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.title = "Equipo de Desarrollo"

        poblarDatos()
    }

    private fun poblarDatos() {

        // --- DESARROLLADOR 1: SAVKA CARVAJAL GONZALEZ ---
        binding.textNombre.text = "Savka Carvajal Gonzalez"
        binding.textRol.text = "Rol: FULL STACK"
        binding.textCorreo.text = "savkacarvajalg@gmail.com"
        binding.textInstitucion.text = "INACAP La Serena / Ingeniería en Ciberseguridad"
        // Enlace de GitHub (Requiere autoLink en el XML)
        binding.textGithub.text = "https://github.com/savkacarvajal"

        // --- DESARROLLADOR 2: DANTE GUTIERREZ BAEZA ---
        binding.textNombre2.text = "Dante Gutierrez Baeza"
        binding.textRol2.text = "Rol: Graphic Designer"
        binding.textCorreo2.text = "dante.gutierrez@inacapmail.cl"
        binding.textInstitucion2.text = "INACAP La Serena / Ingeniería en Informática"
        // Enlace de GitHub (Requiere autoLink en el XML)
        binding.textGithub2.text = "https://github.com/DantePleto"
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressedDispatcher.onBackPressed()
        return true
    }
}
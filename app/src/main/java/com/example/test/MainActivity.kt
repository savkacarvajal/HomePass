package com.example.test

import android.content.Intent
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import androidx.appcompat.app.AppCompatActivity
// 1. Import View Binding
import com.example.test.databinding.ActivityMainBinding
import java.text.SimpleDateFormat
import java.util.*

class MainActivity : AppCompatActivity() {

    // 2. Declare View Binding
    private lateinit var binding: ActivityMainBinding

    private val handler = Handler(Looper.getMainLooper())
    private val runnable = object : Runnable {
        override fun run() {
            updateDateTime()
            handler.postDelayed(this, 1000)
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        // 3. Initialize View Binding
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // 4. Use 'binding' to set listeners (no more findViewById)
        binding.cardGestionUsuarios.setOnClickListener {
            val intent = Intent(this, GestionUsuarioActivity::class.java)
            startActivity(intent)
        }

        binding.cardSensores.setOnClickListener {
            val intent = Intent(this, SensoresActivity::class.java)
            startActivity(intent)
        }

        binding.cardDesarrollador.setOnClickListener {
            val intent = Intent(this, DesarrolladorActivity::class.java)
            startActivity(intent)
        }
    }

    override fun onResume() {
        super.onResume()
        handler.post(runnable)
    }

    override fun onPause() {
        super.onPause()
        handler.removeCallbacks(runnable)
    }

    private fun updateDateTime() {
        val sdf = SimpleDateFormat("dd/MM/yyyy HH:mm:ss", Locale.getDefault())
        val currentDate = sdf.format(Date())

        // 5. Use 'binding' to update the TextView
        binding.textViewDateTime.text = currentDate
    }
}
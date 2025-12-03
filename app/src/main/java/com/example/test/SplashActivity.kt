package com.example.test

import android.content.Intent
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import androidx.appcompat.app.AppCompatActivity
// 1. Importar View Binding (Recomendación)
import com.example.test.databinding.ActivitySplashBinding

class SplashActivity : AppCompatActivity() {

    // 2. Declarar View Binding (Recomendación)
    private lateinit var binding: ActivitySplashBinding

    // 3. Constante para el tiempo (Tu lógica)
    private val SPLASH_TIME_OUT: Long = 7000

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // 4. Inicializar View Binding (Recomendación)
        binding = ActivitySplashBinding.inflate(layoutInflater)
        setContentView(binding.root)
        // Ya no se usa: setContentView(R.layout.activity_splash)

        // 5. Handler para la redirección (Tu lógica de 7 segundos, ¡está perfecta!)
        Handler(Looper.getMainLooper()).postDelayed({
            val intent = Intent(this, ActLogin::class.java)
            startActivity(intent)
            finish()
        }, SPLASH_TIME_OUT)
    }
}
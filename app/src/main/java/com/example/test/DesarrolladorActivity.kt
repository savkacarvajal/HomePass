package com.example.test

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity

class DesarrolladorActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_desarrollador)

        supportActionBar?.setDisplayHomeAsUpEnabled(true)
        supportActionBar?.title = "Equipo de Desarrollo"
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressedDispatcher.onBackPressed()
        return true
    }
}
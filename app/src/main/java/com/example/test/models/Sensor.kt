package com.example.test.models

import com.google.gson.annotations.SerializedName

/**
 * Modelo de datos para Sensor RFID
 */
data class Sensor(
    @SerializedName("id_sensor")
    val idSensor: Int,

    @SerializedName("id_departamento")
    val idDepartamento: Int,

    @SerializedName("id_usuario")
    val idUsuario: Int,

    @SerializedName("codigo_sensor")
    val codigoSensor: String,

    @SerializedName("nombre_sensor")
    val nombreSensor: String?,

    @SerializedName("tipo")
    val tipo: String, // LLAVERO o TARJETA

    @SerializedName("estado")
    val estado: String, // ACTIVO, INACTIVO, PERDIDO, BLOQUEADO

    @SerializedName("fecha_alta")
    val fechaAlta: String,

    @SerializedName("fecha_baja")
    val fechaBaja: String?,

    @SerializedName("nombre_usuario")
    val nombreUsuario: String?,

    @SerializedName("apellido_usuario")
    val apellidoUsuario: String?,

    @SerializedName("numero_depto")
    val numeroDepartamento: String?,

    @SerializedName("torre")
    val torre: String?
) {
    fun getNombreCompleto(): String {
        return if (!nombreUsuario.isNullOrEmpty() && !apellidoUsuario.isNullOrEmpty()) {
            "$nombreUsuario $apellidoUsuario"
        } else {
            "Usuario desconocido"
        }
    }

    fun getDepartamentoCompleto(): String {
        return if (!numeroDepartamento.isNullOrEmpty() && !torre.isNullOrEmpty()) {
            "$numeroDepartamento-$torre"
        } else {
            "N/A"
        }
    }

    fun getIconoTipo(): String {
        return when (tipo) {
            "LLAVERO" -> "🔑"
            "TARJETA" -> "💳"
            else -> "📟"
        }
    }

    fun getColorEstado(): Int {
        return when (estado) {
            "ACTIVO" -> android.graphics.Color.parseColor("#28a745")
            "INACTIVO" -> android.graphics.Color.parseColor("#6c757d")
            "PERDIDO" -> android.graphics.Color.parseColor("#ffc107")
            "BLOQUEADO" -> android.graphics.Color.parseColor("#dc3545")
            else -> android.graphics.Color.GRAY
        }
    }
}

/**
 * Respuesta de la API de sensores
 */
data class SensoresResponse(
    @SerializedName("success")
    val success: Boolean,

    @SerializedName("datos")
    val datos: List<Sensor>?,

    @SerializedName("total")
    val total: Int?,

    @SerializedName("mensaje")
    val mensaje: String?
)

/**
 * Request para agregar sensor
 */
data class AgregarSensorRequest(
    @SerializedName("id_departamento")
    val idDepartamento: Int,

    @SerializedName("id_usuario")
    val idUsuario: Int,

    @SerializedName("codigo_sensor")
    val codigoSensor: String,

    @SerializedName("nombre_sensor")
    val nombreSensor: String,

    @SerializedName("tipo")
    val tipo: String,

    @SerializedName("estado")
    val estado: String = "ACTIVO"
)

/**
 * Request para actualizar estado de sensor
 */
data class ActualizarEstadoSensorRequest(
    @SerializedName("id_sensor")
    val idSensor: Int,

    @SerializedName("estado")
    val estado: String
)


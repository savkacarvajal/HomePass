package com.example.test.models

import com.google.gson.annotations.SerializedName

/**
 * Modelo de datos para Evento de Acceso
 */
data class Evento(
    @SerializedName("id_evento")
    val idEvento: Int,

    @SerializedName("id_sensor")
    val idSensor: Int?,

    @SerializedName("id_usuario")
    val idUsuario: Int?,

    @SerializedName("id_departamento")
    val idDepartamento: Int,

    @SerializedName("tipo_evento")
    val tipoEvento: String,

    @SerializedName("resultado")
    val resultado: String, // PERMITIDO o DENEGADO

    @SerializedName("codigo_sensor")
    val codigoSensor: String?,

    @SerializedName("fecha_hora")
    val fechaHora: String,

    @SerializedName("observaciones")
    val observaciones: String?,

    @SerializedName("nombre_sensor")
    val nombreSensor: String?,

    @SerializedName("tipo_sensor")
    val tipoSensor: String?,

    @SerializedName("nombre_usuario")
    val nombreUsuario: String?,

    @SerializedName("apellido_usuario")
    val apellidoUsuario: String?,

    @SerializedName("numero_depto")
    val numeroDepartamento: String?,

    @SerializedName("torre")
    val torre: String?
) {
    fun getNombreUsuarioCompleto(): String {
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

    fun getIconoTipoEvento(): String {
        return when (tipoEvento) {
            "ACCESO_VALIDO" -> "✅"
            "ACCESO_RECHAZADO" -> "❌"
            "APERTURA_MANUAL_APP" -> "🔓"
            "CIERRE_MANUAL_APP" -> "🔒"
            "SENSOR_INACTIVO" -> "⏸️"
            "SENSOR_BLOQUEADO" -> "🚫"
            "SENSOR_PERDIDO" -> "⚠️"
            else -> "📋"
        }
    }

    fun getColorResultado(): Int {
        return when (resultado) {
            "PERMITIDO" -> android.graphics.Color.parseColor("#28a745")
            "DENEGADO" -> android.graphics.Color.parseColor("#dc3545")
            else -> android.graphics.Color.GRAY
        }
    }

    fun getTipoEventoLegible(): String {
        return when (tipoEvento) {
            "ACCESO_VALIDO" -> "Acceso Válido"
            "ACCESO_RECHAZADO" -> "Acceso Rechazado"
            "APERTURA_MANUAL_APP" -> "Apertura Manual"
            "CIERRE_MANUAL_APP" -> "Cierre Manual"
            "SENSOR_INACTIVO" -> "Sensor Inactivo"
            "SENSOR_BLOQUEADO" -> "Sensor Bloqueado"
            "SENSOR_PERDIDO" -> "Sensor Perdido"
            else -> tipoEvento
        }
    }
}

/**
 * Respuesta de la API de eventos
 */
data class EventosResponse(
    @SerializedName("success")
    val success: Boolean,

    @SerializedName("datos")
    val datos: List<Evento>?,

    @SerializedName("total")
    val total: Int?,

    @SerializedName("mensaje")
    val mensaje: String?
)

/**
 * Modelo para estadísticas de eventos
 */
data class Estadisticas(
    @SerializedName("total_eventos")
    val totalEventos: Int,

    @SerializedName("accesos_permitidos")
    val accesosPermitidos: Int,

    @SerializedName("accesos_denegados")
    val accesosDenegados: Int,

    @SerializedName("porcentaje_exito")
    val porcentajeExito: Double,

    @SerializedName("por_tipo")
    val porTipo: Map<String, Int>?
)

/**
 * Respuesta de estadísticas
 */
data class EstadisticasResponse(
    @SerializedName("success")
    val success: Boolean,

    @SerializedName("estadisticas")
    val estadisticas: Estadisticas?,

    @SerializedName("periodo")
    val periodo: Periodo?,

    @SerializedName("mensaje")
    val mensaje: String?
)

data class Periodo(
    @SerializedName("desde")
    val desde: String,

    @SerializedName("hasta")
    val hasta: String
)


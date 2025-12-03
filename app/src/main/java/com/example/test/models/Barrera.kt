package com.example.test.models

import com.google.gson.annotations.SerializedName

/**
 * Modelo para estado de la barrera
 */
data class EstadoBarrera(
    @SerializedName("estado_actual")
    val estadoActual: String, // ABIERTA o CERRADA

    @SerializedName("ultimo_cambio")
    val ultimoCambio: String?,

    @SerializedName("accion")
    val accion: String?,

    @SerializedName("responsable")
    val responsable: String?
)

/**
 * Respuesta del estado de barrera
 */
data class EstadoBarreraResponse(
    @SerializedName("success")
    val success: Boolean,

    @SerializedName("estado_actual")
    val estadoActual: String?,

    @SerializedName("ultimo_cambio")
    val ultimoCambio: String?,

    @SerializedName("accion")
    val accion: String?,

    @SerializedName("responsable")
    val responsable: String?,

    @SerializedName("mensaje")
    val mensaje: String?
)

/**
 * Request para abrir/cerrar barrera
 */
data class ControlBarreraRequest(
    @SerializedName("id_usuario")
    val idUsuario: Int,

    @SerializedName("id_departamento")
    val idDepartamento: Int
)

/**
 * Respuesta de control de barrera
 */
data class ControlBarreraResponse(
    @SerializedName("success")
    val success: Boolean,

    @SerializedName("mensaje")
    val mensaje: String,

    @SerializedName("estado_actual")
    val estadoActual: String?,

    @SerializedName("tiempo_auto_cierre")
    val tiempoAutoCierre: Int?
)


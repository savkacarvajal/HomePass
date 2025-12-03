package com.example.test

/**
 * Constantes globales de la aplicación HomePass IoT
 */
object Constants {
    // Configuración del servidor
    const val BASE_URL = "http://44.199.155.199"

    // Endpoints de la API
    object API {
        // Autenticación y Usuarios
        const val LOGIN = "$BASE_URL/apiconsultausu.php"
        const val REGISTER = "$BASE_URL/register.php"
        const val MODIFICAR_CLAVE = "$BASE_URL/apimodificarclave.php"
        const val SOLICITAR_CODIGO = "$BASE_URL/solicitar_codigo.php"
        const val VALIDAR_CODIGO = "$BASE_URL/validar_codigo.php"
        const val GET_USERS = "$BASE_URL/get_users.php"
        const val UPDATE_USER = "$BASE_URL/update_user.php"
        const val DELETE_USER = "$BASE_URL/delete_user.php"

        // APIs IoT
        const val SENSORES_LISTAR = "$BASE_URL/api_sensores.php?accion=listar"
        const val SENSORES_POR_DEPARTAMENTO = "$BASE_URL/api_sensores.php?accion=por_departamento"
        const val SENSORES_AGREGAR = "$BASE_URL/api_sensores.php?accion=agregar"
        const val SENSORES_ACTUALIZAR_ESTADO = "$BASE_URL/api_sensores.php?accion=actualizar_estado"
        const val SENSORES_ELIMINAR = "$BASE_URL/api_sensores.php?accion=eliminar"

        const val BARRERA_ESTADO = "$BASE_URL/api_barrera.php?accion=estado"
        const val BARRERA_ABRIR = "$BASE_URL/api_barrera.php?accion=abrir"
        const val BARRERA_CERRAR = "$BASE_URL/api_barrera.php?accion=cerrar"

        const val EVENTOS_RECIENTES = "$BASE_URL/api_eventos.php?accion=recientes"
        const val EVENTOS_POR_DEPARTAMENTO = "$BASE_URL/api_eventos.php?accion=por_departamento"
        const val EVENTOS_ESTADISTICAS = "$BASE_URL/api_eventos.php?accion=estadisticas"
    }

    // Roles de usuario
    object Roles {
        const val ADMINISTRADOR = "ADMINISTRADOR"
        const val OPERADOR = "OPERADOR"
    }

    // Estados de sensores
    object EstadosSensor {
        const val ACTIVO = "ACTIVO"
        const val INACTIVO = "INACTIVO"
        const val PERDIDO = "PERDIDO"
        const val BLOQUEADO = "BLOQUEADO"
    }

    // Tipos de sensores
    object TiposSensor {
        const val LLAVERO = "LLAVERO"
        const val TARJETA = "TARJETA"
    }

    // SharedPreferences Keys
    object Prefs {
        const val PREFS_NAME = "HomePassPrefs"
        const val KEY_USER_ID = "user_id"
        const val KEY_USER_EMAIL = "user_email"
        const val KEY_USER_NAME = "user_name"
        const val KEY_USER_ROL = "user_rol"
        const val KEY_DEPARTAMENTO_ID = "departamento_id"
        const val KEY_IS_LOGGED_IN = "is_logged_in"
    }

    // Códigos de resultado
    object ResultCodes {
        const val SENSOR_AGREGADO = 1001
        const val SENSOR_MODIFICADO = 1002
        const val SENSOR_ELIMINADO = 1003
    }
}


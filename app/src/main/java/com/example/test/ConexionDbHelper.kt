package com.example.test

import android.content.Context
import android.database.sqlite.SQLiteDatabase
import android.database.sqlite.SQLiteOpenHelper

// Nombres de la base de datos y la tabla según tus notas
private val DATABASE_NAME = "APPSQLITE"
private val DATABASE_VERSION = 1

// Nombres de las columnas (basado en la lógica de guardar() en tus notas)
private val TABLE_USUARIOS = "USUARIOS"
private val COLUMN_ID = "ID"
private val COLUMN_NOMBRE = "NOMBRE"
private val COLUMN_APELLIDO = "APELLIDO"
private val COLUMN_EMAIL = "EMAIL"
private val COLUMN_CLAVE = "CLAVE"

class ConexionDbHelper(context: Context) : SQLiteOpenHelper(
    context, DATABASE_NAME, null, DATABASE_VERSION
) {

    // Script SQL para crear la tabla USUARIOS
    private val SQL_CREATE_ENTRIES =
        """
        CREATE TABLE $TABLE_USUARIOS (
            $COLUMN_ID INTEGER PRIMARY KEY AUTOINCREMENT,
            $COLUMN_NOMBRE TEXT NOT NULL,
            $COLUMN_APELLIDO TEXT NOT NULL,
            $COLUMN_EMAIL TEXT, 
            $COLUMN_CLAVE TEXT NOT NULL
        )
        """

    // Script SQL para borrar la tabla (se usa en onUpgrade)
    private val SQL_DELETE_ENTRIES = "DROP TABLE IF EXISTS $TABLE_USUARIOS"


    // Método llamado la primera vez que se accede a la base de datos
    override fun onCreate(db: SQLiteDatabase) {
        db.execSQL(SQL_CREATE_ENTRIES)
    }

    // Método llamado si la versión de la base de datos cambia
    override fun onUpgrade(db: SQLiteDatabase, oldVersion: Int, newVersion: Int) {
        db.execSQL(SQL_DELETE_ENTRIES)
        onCreate(db)
    }

    // Método para manejar downgrades
    override fun onDowngrade(db: SQLiteDatabase, oldVersion: Int, newVersion: Int) {
        onUpgrade(db, oldVersion, newVersion)
    }
}
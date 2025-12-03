// build.gradle (Módulo: app) - Usando Sintaxis Kotlin DSL

plugins {
    alias(libs.plugins.android.application)
    alias(libs.plugins.kotlin.android)
}

android {
    namespace = "com.example.test"
    compileSdk = 36

    buildFeatures {
        viewBinding = true
    }
    // ---------------------------------------------------

    defaultConfig {
        applicationId = "com.example.test"
        minSdk = 24
        targetSdk = 36
        versionCode = 1
        versionName = "1.0"

        testInstrumentationRunner = "androidx.test.runner.AndroidJUnitRunner"
    }

    buildTypes {
        release {
            isMinifyEnabled = false
            proguardFiles(
                getDefaultProguardFile("proguard-android-optimize.txt"),
                "proguard-rules.pro"
            )
        }
    }
    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_11
        targetCompatibility = JavaVersion.VERSION_11
    }
    kotlinOptions {
        jvmTarget = "11"
    }
}

dependencies {

    implementation(libs.androidx.core.ktx)
    implementation(libs.androidx.appcompat)
    implementation(libs.material)
    implementation(libs.androidx.activity)
    implementation(libs.androidx.constraintlayout)

    // Dependencias para los requerimientos:
    implementation("com.airbnb.android:lottie:6.4.0")          // Para Splash Screen (Punto 1)
    implementation("com.github.f0ris.sweetalert:library:1.6.2")  // Para todos los diálogos (Puntos 2, 3, 4, 5, etc.)
    implementation("com.android.volley:volley:1.2.1")             // Para la conexión de red (Login, Punto 2)
    implementation("androidx.lifecycle:lifecycle-runtime-ktx:2.7.0")

    // Dependencias IoT HomePass
    implementation("com.squareup.okhttp3:okhttp:4.12.0")         // Para peticiones HTTP
    implementation("com.google.code.gson:gson:2.10.1")           // Para parsear JSON
    implementation("androidx.swiperefreshlayout:swiperefreshlayout:1.1.0") // Para pull-to-refresh
    implementation("androidx.recyclerview:recyclerview:1.3.2")   // Para listas

    testImplementation(libs.junit)
    androidTestImplementation(libs.androidx.junit)
    androidTestImplementation(libs.androidx.espresso.core)
}
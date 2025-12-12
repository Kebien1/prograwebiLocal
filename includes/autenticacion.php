<?php
// Archivo: includes/autenticacion.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Si no existe la sesión, redirigir
if (!isset($_SESSION['user_id'])) {
    // Si tenemos la variable $base_url cargada (gracias al cambio en el paso 2), úsala.
    if (isset($base_url)) {
        header("Location: " . $base_url . "modules/auth/login.php");
    } else {
        // Fallback de emergencia si no se cargó config
        header("Location: /PROYECTOLOCALHOST/modules/auth/login.php");
    }
    exit;
}
?>
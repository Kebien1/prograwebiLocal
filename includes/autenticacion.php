<?php
// Archivo: includes/autenticacion.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Si no existe la variable de sesión 'user_id', no está logueado
if (!isset($_SESSION['user_id'])) {
    // Usamos la ruta relativa para salir hacia la raíz y entrar a modules/auth
    // Nota: Asumimos que $base_url está disponible o usamos ruta relativa de seguridad
    header("Location: ../../modules/auth/login.php");
    exit;
}
?>
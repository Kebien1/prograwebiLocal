<?php
// Archivo: includes/verificar_rol.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function verificarRol($rolesPermitidos) {
    // 1. Si no hay sesión, mandar al login
    // Usamos una ruta absoluta relativa a la raíz del servidor o configurada en bd.php
    if (!isset($_SESSION['rol'])) {
        // Ajusta "/PROYECTOLOCALHOST/" si tu carpeta se llama diferente
        header("Location: /PROYECTOLOCALHOST/modules/auth/login.php");
        exit;
    }

    // 2. Si el rol del usuario no está en la lista permitida, mostrar error
    if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
        // Cargamos bootstrap simple para el mensaje de error
        echo '
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <div class="container mt-5">
            <div class="alert alert-danger text-center shadow">
                <h1 class="display-4"><i class="bi bi-shield-lock-fill"></i> Acceso Denegado</h1>
                <p class="lead">No tienes permisos para ver esta página.</p>
                <a href="/PROYECTOLOCALHOST/index.php" class="btn btn-danger">Volver al Inicio</a>
            </div>
        </div>';
        exit;
    }
}
?>
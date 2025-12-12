<?php
// Si no se ha iniciado sesión (por si acaso), lo iniciamos para leer el nombre del usuario
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema PrograWeb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo $base_url; ?>dashboard.php">
            <i class="bi bi-code-slash me-2"></i>PrograWeb
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <?php if(isset($_SESSION['user_id'])): ?>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo $base_url; ?>modules/usuarios/index.php">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo $base_url; ?>modules/roles/index.php">Roles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo $base_url; ?>modules/permisos/index.php">Permisos</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white small">Hola, <strong><?php echo $_SESSION['nick']; ?></strong></span>
                <a href="<?php echo $base_url; ?>modules/auth/logout.php" class="btn btn-sm btn-danger">Cerrar Sesión</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
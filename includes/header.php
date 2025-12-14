<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

// Asegurarnos de que base_url esté disponible. 
// Si config/bd.php no se incluyó antes, esto evita errores visuales aunque no funcionen los links.
if(!isset($base_url)) { $base_url = "/PROYECTOLOCALHOST/"; }
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plataforma Educativa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* Estilo simple para el hover de las tarjetas */
        .card { transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <?php 
            $home_link = $base_url . "index.php";
            if(isset($_SESSION['rol'])){
                switch($_SESSION['rol']){
                    case 1: $home_link = $base_url . "modules/admin/dashboard.php"; break;
                    case 2: $home_link = $base_url . "modules/estudiante/dashboard.php"; break;
                    case 3: $home_link = $base_url . "modules/docente/dashboard.php"; break;
                }
            }
        ?>
        <a class="navbar-brand fw-bold text-warning" href="<?php echo $home_link; ?>">
            <i class="bi bi-code-slash me-2"></i>PrograWeb
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <?php if(isset($_SESSION['user_id'])): ?>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
                <?php if($_SESSION['rol'] == 1): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo $base_url; ?>modules/admin/usuarios/index.php">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo $base_url; ?>modules/admin/roles/index.php">Roles</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo $base_url; ?>modules/admin/permisos/index.php">Permisos</a></li>
                <?php endif; ?>

                <?php if($_SESSION['rol'] == 2): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo $base_url; ?>modules/estudiante/mis-cursos/index.php">Mis Clases</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo $base_url; ?>modules/estudiante/cursos/index.php">Catálogo</a></li>
                <?php endif; ?>

                <?php if($_SESSION['rol'] == 3): ?>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo $base_url; ?>modules/docente/cursos/index.php">Mis Cursos</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?php echo $base_url; ?>modules/docente/calificaciones/index.php">Notas</a></li>
                <?php endif; ?>

            </ul>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white small border-end pe-3">
                    <i class="bi bi-person-circle me-1"></i> <?php echo $_SESSION['nick']; ?>
                </span>
                <a href="<?php echo $base_url; ?>modules/auth/logout.php" class="btn btn-sm btn-danger fw-bold">Salir</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container" style="min-height: 80vh;">
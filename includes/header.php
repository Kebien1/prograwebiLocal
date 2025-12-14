<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Definir color y nombre según el rol
$navbarColor = "bg-primary"; 
$rolNombre = "Estudiante";

if(isset($_SESSION['rol_id'])) {
    if($_SESSION['rol_id'] == 1) { 
        $navbarColor = "bg-dark"; // Admin
        $rolNombre = "Administrador";
    }
    if($_SESSION['rol_id'] == 2) { 
        $navbarColor = "bg-success"; // Docente
        $rolNombre = "Docente";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduPlatform - <?php echo $rolNombre; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark <?php echo $navbarColor; ?> shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php">
        <i class="bi bi-speedometer2"></i> EduPlatform
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="userNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <li class="nav-item me-3">
                <span class="badge bg-warning text-dark border border-light">
                    Plan <?php echo $_SESSION['plan_nombre'] ?? 'Básico'; ?>
                </span>
            </li>
            
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white active fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nombre'] ?? 'Usuario'; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><span class="dropdown-header">Rol: <?php echo $rolNombre; ?></span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="../../modules/auth/logout.php">
                        <i class="bi bi-power"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
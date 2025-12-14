<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Color de la barra según el rol
$navbarColor = "bg-primary"; // Azul por defecto (Estudiante)
$rolNombre = "Estudiante";

if(isset($_SESSION['rol_id'])) {
    if($_SESSION['rol_id'] == 1) { 
        $navbarColor = "bg-dark"; // Admin = Negro
        $rolNombre = "Administrador";
    }
    if($_SESSION['rol_id'] == 2) { 
        $navbarColor = "bg-success"; // Docente = Verde
        $rolNombre = "Docente";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel <?php echo $rolNombre; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark <?php echo $navbarColor; ?> mb-4 shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php">
        <i class="bi bi-speedometer2"></i> EduPlatform
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="userNav">
      <ul class="navbar-nav ms-auto align-items-center">
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
                <a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>modules/auth/logout.php">
                    <i class="bi bi-power"></i> Cerrar Sesión
                </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mb-5"> ```

**Ubicación:** `includes/footer.php`

```php
</div> <footer class="footer mt-auto py-3 bg-white text-center border-top">
    <div class="container">
        <span class="text-muted text-small">&copy; <?php echo date('Y'); ?> EduPlatform - Sistema de Gestión Educativa</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
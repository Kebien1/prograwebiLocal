<?php
// 1. Incluimos la configuración para conectar a la Base de Datos
require_once 'config/bd.php';

// 2. Consultamos los planes disponibles para mostrarlos dinámicamente
try {
    $stmt = $conexion->query("SELECT * FROM planes ORDER BY precio ASC");
    $planes = $stmt->fetchAll();
} catch (Exception $e) {
    $planes = []; // Si hay error, evitamos que la página se rompa
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduPlatform | Aprende Programación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* Único estilo extra para el degradado del banner */
        .hero-gradient {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="#">
                <i class="bi bi-code-square"></i> EduPlatform
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto gap-2 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#planes">Planes</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="modules/auth/login.php" class="btn btn-outline-light btn-sm px-3">
                            <i class="bi bi-box-arrow-in-right"></i> Ingresar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="modules/auth/registro.php" class="btn btn-primary btn-sm px-3 fw-bold">
                            ¡Regístrate Gratis!
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-gradient text-white pt-5 mt-5 pb-5 mb-5 text-center">
        <div class="container py-5">
            <h1 class="display-3 fw-bold mb-3">Domina el Código</h1>
            <p class="lead mb-4 fs-4">Accede a la mejor biblioteca de libros y cursos de programación.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="modules/auth/registro.php" class="btn btn-light btn-lg text-primary fw-bold px-5 shadow">
                    Empezar Ahora
                </a>
                <a href="#planes" class="btn btn-outline-light btn-lg px-4">
                    Ver Precios
                </a>
            </div>
        </div>
    </header>

    <section id="planes" class="container mb-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Elige tu Plan de Aprendizaje</h2>
            <p class="text-muted fs-5">Controla desde cuántos dispositivos puedes aprender simultáneamente.</p>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
            <?php foreach ($planes as $plan): ?>
                <div class="col">
                    <div class="card h-100 shadow border-0 hover-shadow" style="transition: transform 0.3s;">
                        
                        <div class="card-header py-3 text-center <?php echo ($plan['nombre'] == 'Pro') ? 'bg-primary text-white' : 'bg-white'; ?>">
                            <h3 class="my-0 fw-normal"><?php echo htmlspecialchars($plan['nombre']); ?></h3>
                        </div>

                        <div class="card-body text-center d-flex flex-column">
                            <h1 class="card-title pricing-card-title fw-bold">
                                $<?php echo number_format($plan['precio'], 0); ?>
                                <small class="text-muted fw-light fs-4">/mes</small>
                            </h1>
                            
                            <ul class="list-unstyled mt-3 mb-4 flex-grow-1">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Acceso a Cursos</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Biblioteca de Libros</li>
                                <li class="mb-2 py-2 bg-light rounded fw-bold text-primary">
                                    <i class="bi bi-laptop"></i> <?php echo $plan['limite_sesiones']; ?> Dispositivo(s)
                                </li>
                                <li class="text-muted small px-3">
                                    <?php echo htmlspecialchars($plan['descripcion']); ?>
                                </li>
                            </ul>

                            <a href="modules/auth/registro.php?plan=<?php echo $plan['id']; ?>" class="w-100 btn btn-lg <?php echo ($plan['nombre'] == 'Pro') ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                Elegir <?php echo $plan['nombre']; ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="bg-light py-5">
        <div class="container text-center">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-3">
                        <i class="bi bi-shield-lock-fill display-4 text-primary mb-3"></i>
                        <h4>Seguridad Total</h4>
                        <p class="text-muted">Tu cuenta protegida con control de sesiones inteligente.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3">
                        <i class="bi bi-collection-play-fill display-4 text-danger mb-3"></i>
                        <h4>Contenido HD</h4>
                        <p class="text-muted">Cursos en alta calidad y libros descargables en PDF.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3">
                        <i class="bi bi-trophy-fill display-4 text-warning mb-3"></i>
                        <h4>Certifícate</h4>
                        <p class="text-muted">Avanza en tu carrera profesional con nosotros.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer mt-auto py-4 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-1">&copy; <?php echo date('Y'); ?> EduPlatform. Todos los derechos reservados.</p>
            <small class="text-white-50">Desarrollado con PHP y Bootstrap 5</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
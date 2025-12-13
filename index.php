<?php
// Archivo: index.php
session_start();
include("config/bd.php");

// Verificamos sesión para cambiar los botones del menú
$logueado = isset($_SESSION['user_id']);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PrograWeb Academy - Cursos Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-code-slash me-2 text-warning"></i>PrograWeb
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#beneficios">Beneficios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#cursos">Cursos</a></li>
                    <li class="nav-item ms-lg-3">
                        <?php if($logueado): ?>
                            <a href="dashboard.php" class="btn btn-warning fw-bold text-dark">
                                <i class="bi bi-speedometer2 me-1"></i> Ir al Panel
                            </a>
                        <?php else: ?>
                            <div class="d-flex gap-2">
                                <a href="modules/auth/login.php" class="btn btn-outline-light btn-sm">Ingresar</a>
                                <a href="modules/auth/registro.php" class="btn btn-primary btn-sm fw-bold">Registrarse</a>
                            </div>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header id="inicio" class="d-flex align-items-center text-center text-white" 
            style="min-height: 100vh; background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1517694712202-14dd9538aa97?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80') center/cover no-repeat;">
        
        <div class="container pt-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill">🎓 Nueva Plataforma Educativa</span>
                    <h1 class="display-3 fw-bold mb-3">Aprende a Programar desde Cero</h1>
                    <p class="lead mb-5 text-light opacity-75">Domina las tecnologías web más demandadas. Cursos prácticos, proyectos reales y certificación al finalizar.</p>
                    <div class="d-flex gap-3 justify-content-center flex-column flex-sm-row">
                        <?php if(!$logueado): ?>
                            <a href="modules/auth/registro.php" class="btn btn-primary btn-lg fw-bold px-5 shadow">Crear Cuenta Gratis</a>
                        <?php else: ?>
                            <a href="dashboard.php" class="btn btn-success btn-lg fw-bold px-5 shadow">Continuar Mis Clases</a>
                        <?php endif; ?>
                        <a href="#cursos" class="btn btn-outline-light btn-lg px-4">Ver Cursos</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="beneficios" class="py-5 bg-white">
        <div class="container py-5">
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="p-4 rounded-3 border h-100 bg-light">
                        <div class="text-primary mb-3">
                            <i class="bi bi-camera-video-fill" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="fw-bold h4">Clases en Video</h3>
                        <p class="text-muted">Alta calidad de imagen y sonido. Aprende a tu propio ritmo con lecciones paso a paso.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-3 border h-100 bg-light">
                        <div class="text-success mb-3">
                            <i class="bi bi-file-earmark-code-fill" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="fw-bold h4">Proyectos Reales</h3>
                        <p class="text-muted">No solo teoría. Construirás aplicaciones reales para tu portafolio profesional.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-3 border h-100 bg-light">
                        <div class="text-warning mb-3">
                            <i class="bi bi-award-fill" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="fw-bold h4">Certificación</h3>
                        <p class="text-muted">Recibe un diploma digital verificable al completar satisfactoriamente cada curso.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="cursos" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold">Cursos Populares</h2>
                <p class="text-muted lead">Explora nuestras rutas de aprendizaje más solicitadas.</p>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="bg-dark text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-filetype-php display-1"></i>
                        </div>
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">Backend</span>
                            <h5 class="card-title fw-bold">PHP & MySQL Master</h5>
                            <p class="card-text text-muted">Aprende a crear sistemas web dinámicos, gestión de usuarios y seguridad.</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-4">
                            <button class="btn btn-outline-primary w-100 fw-bold">Más Información</button>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="bg-primary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-bootstrap-fill display-1"></i>
                        </div>
                        <div class="card-body">
                            <span class="badge bg-info text-dark mb-2">Frontend</span>
                            <h5 class="card-title fw-bold">Bootstrap 5 Desde Cero</h5>
                            <p class="card-text text-muted">Diseña sitios web modernos, responsivos y rápidos sin escribir CSS complejo.</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-4">
                            <button class="btn btn-outline-primary w-100 fw-bold">Más Información</button>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="bg-warning text-dark d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-database-fill-gear display-1"></i>
                        </div>
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2">Base de Datos</span>
                            <h5 class="card-title fw-bold">SQL Avanzado</h5>
                            <p class="card-text text-muted">Optimización de consultas, procedimientos almacenados y diseño relacional.</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-4">
                            <button class="btn btn-outline-primary w-100 fw-bold">Más Información</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-primary text-white text-center">
        <div class="container py-4">
            <h2 class="display-6 fw-bold mb-3">¿Listo para cambiar tu futuro?</h2>
            <p class="lead mb-4">Únete a nuestra comunidad hoy mismo y empieza a programar.</p>
            <?php if(!$logueado): ?>
                <a href="modules/auth/registro.php" class="btn btn-light btn-lg px-5 fw-bold text-primary shadow">¡Regístrate Gratis!</a>
            <?php else: ?>
                <a href="dashboard.php" class="btn btn-light btn-lg px-5 fw-bold text-primary shadow">Ir a mi Panel</a>
            <?php endif; ?>
        </div>
    </section>

    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold text-warning mb-3"><i class="bi bi-code-slash me-2"></i>PrograWeb</h5>
                    <p class="text-white-50">Plataforma educativa dedicada a formar desarrolladores profesionales con las mejores prácticas del mercado.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Plataforma</h6>
                    <ul class="list-unstyled text-white-50">
                        <li><a href="#" class="text-reset text-decoration-none">Cursos</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Precios</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">Soporte</h6>
                    <ul class="list-unstyled text-white-50">
                        <li><a href="#" class="text-reset text-decoration-none">Ayuda</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Contacto</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Términos</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6 class="fw-bold mb-3">Contacto</h6>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> contacto@prograweb.com</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> +1 234 567 890</li>
                        <li><i class="bi bi-geo-alt me-2"></i> Ciudad Tecnológica, Web</li>
                    </ul>
                </div>
            </div>
            <div class="border-top border-secondary mt-4 pt-4 text-center text-white-50 small">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> PrograWeb Academy. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
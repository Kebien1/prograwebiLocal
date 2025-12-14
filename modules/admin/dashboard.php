<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(1); // Solo Admin (ID 1)
require_once '../../includes/header.php';

// CONSULTAS PARA ESTADÍSTICAS REALES
// 1. Total usuarios
$totalUsers = $conexion->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
// 2. Sesiones activas ahora mismo
$activeSessions = $conexion->query("SELECT COUNT(*) FROM sesiones_activas")->fetchColumn();
// 3. Ventas totales (suma de monto_pagado)
$totalSales = $conexion->query("SELECT SUM(monto_pagado) FROM compras")->fetchColumn();
if(!$totalSales) $totalSales = 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark mb-0">Resumen General</h2>
    <a href="#" class="btn btn-dark"><i class="bi bi-gear-fill"></i> Configuración</a>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card bg-dark text-white h-100 border-0 shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-white-50 text-uppercase fw-bold small">Usuarios Registrados</span>
                        <h2 class="display-4 fw-bold mt-2 mb-0"><?php echo $totalUsers; ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-10 p-3 rounded">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="#" class="text-white text-decoration-none small">Ver detalles <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-primary text-white h-100 border-0 shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-white-50 text-uppercase fw-bold small">En Línea Ahora</span>
                        <h2 class="display-4 fw-bold mt-2 mb-0"><?php echo $activeSessions; ?></h2>
                        <small class="text-white-50">Dispositivos conectados</small>
                    </div>
                    <div class="bg-white bg-opacity-10 p-3 rounded">
                        <i class="bi bi-activity fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success text-white h-100 border-0 shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-white-50 text-uppercase fw-bold small">Ingresos Totales</span>
                        <h2 class="display-4 fw-bold mt-2 mb-0">$<?php echo number_format($totalSales, 0); ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-10 p-3 rounded">
                        <i class="bi bi-currency-dollar fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="fw-bold mb-3">Gestión de la Plataforma</h4>
<div class="row row-cols-1 row-cols-md-3 g-4">
    <div class="col">
        <a href="#" class="card card-link text-decoration-none h-100 border shadow-sm hover-shadow">
            <div class="card-body text-center p-4">
                <i class="bi bi-person-lines-fill display-5 text-primary mb-3"></i>
                <h5 class="text-dark">Usuarios</h5>
                <p class="text-muted small mb-0">Administrar roles y accesos.</p>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="#" class="card card-link text-decoration-none h-100 border shadow-sm">
            <div class="card-body text-center p-4">
                <i class="bi bi-tags-fill display-5 text-primary mb-3"></i>
                <h5 class="text-dark">Planes</h5>
                <p class="text-muted small mb-0">Editar precios y límites de sesión.</p>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="#" class="card card-link text-decoration-none h-100 border shadow-sm">
            <div class="card-body text-center p-4">
                <i class="bi bi-collection-play-fill display-5 text-primary mb-3"></i>
                <h5 class="text-dark">Contenido</h5>
                <p class="text-muted small mb-0">Administrar cursos y libros.</p>
            </div>
        </a>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
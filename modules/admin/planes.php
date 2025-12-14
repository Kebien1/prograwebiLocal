<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(1);
require_once '../../includes/header.php';

$planes = $conexion->query("SELECT * FROM planes")->fetchAll();
?>

<h2 class="fw-bold mb-4 text-dark"><i class="bi bi-tags-fill"></i> Configuración de Planes</h2>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($planes as $p): ?>
    <div class="col">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0 fw-bold text-primary"><?php echo $p['nombre']; ?></h4>
            </div>
            <div class="card-body">
                <h2 class="display-5 fw-bold mb-3">$<?php echo number_format($p['precio'], 0); ?></h2>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Sesiones Simultáneas
                        <span class="badge bg-primary rounded-pill"><?php echo $p['limite_sesiones']; ?></span>
                    </li>
                    <li class="list-group-item text-muted small">
                        <?php echo $p['descripcion'] ?? 'Sin descripción'; ?>
                    </li>
                </ul>
                <a href="plan_editar.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-dark w-100">
                    <i class="bi bi-gear"></i> Editar Reglas
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>
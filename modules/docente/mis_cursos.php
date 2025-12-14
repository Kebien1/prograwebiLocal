<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(2); // Solo Docente
require_once '../../includes/header.php';

// Consultar cursos creados por ESTE docente
$sql = "SELECT * FROM cursos WHERE docente_id = ? ORDER BY id DESC";
$stmt = $conexion->prepare($sql);
$stmt->execute([$_SESSION['usuario_id']]);
$misCursos = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-success"><i class="bi bi-collection-play"></i> Mis Cursos Impartidos</h2>
    <a href="crear_curso.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Nuevo Curso</a>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach($misCursos as $c): ?>
    <div class="col">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white pt-3">
                <h5 class="card-title fw-bold mb-0"><?php echo htmlspecialchars($c['titulo']); ?></h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2"><?php echo htmlspecialchars($c['descripcion']); ?></p>
                <div class="fw-bold text-success fs-4">$<?php echo number_format($c['precio'], 0); ?></div>
            </div>
            <div class="card-footer bg-white border-0 pb-3 d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm flex-grow-1">Editar</button>
                <button class="btn btn-outline-secondary btn-sm flex-grow-1">Alumnos</button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if(empty($misCursos)): ?>
    <div class="alert alert-warning text-center">
        Aún no has creado ningún curso. ¡Empieza hoy!
    </div>
<?php endif; ?>

<?php require_once '../../includes/footer.php'; ?>
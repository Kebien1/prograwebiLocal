<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(3); 
require_once '../../includes/header.php';

// Consultar compras del usuario
// Usamos UNION para traer cursos y libros en una sola lista si es necesario, 
// o consultas separadas. Para simpleza, haremos dos consultas.

// 1. Cursos comprados
$sqlCursos = "SELECT c.id, c.titulo, c.descripcion, comp.fecha_compra 
              FROM compras comp 
              JOIN cursos c ON comp.item_id = c.id 
              WHERE comp.usuario_id = ? AND comp.tipo_item = 'curso'";
$misCursos = $conexion->prepare($sqlCursos);
$misCursos->execute([$_SESSION['usuario_id']]);
$listaCursos = $misCursos->fetchAll();

// 2. Libros comprados
$sqlLibros = "SELECT l.id, l.titulo, l.archivo_pdf, comp.fecha_compra 
              FROM compras comp 
              JOIN libros l ON comp.item_id = l.id 
              WHERE comp.usuario_id = ? AND comp.tipo_item = 'libro'";
$misLibros = $conexion->prepare($sqlLibros);
$misLibros->execute([$_SESSION['usuario_id']]);
$listaLibros = $misLibros->fetchAll();
?>

<h2 class="fw-bold mb-4">Mis Adquisiciones</h2>

<?php if(isset($_GET['exito'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        ¡Compra realizada con éxito! Ya tienes acceso al contenido.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-laptop"></i> Mis Cursos Activos
    </div>
    <div class="card-body">
        <?php if(empty($listaCursos)): ?>
            <p class="text-muted">No tienes cursos comprados.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach($listaCursos as $curso): ?>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold"><?php echo htmlspecialchars($curso['titulo']); ?></h5>
                            <small class="text-muted">Comprado el: <?php echo date('d/m/Y', strtotime($curso['fecha_compra'])); ?></small>
                        </div>
                        <button class="btn btn-sm btn-primary">Ver Clases</button>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-success text-white">
        <i class="bi bi-book"></i> Mi Biblioteca
    </div>
    <div class="card-body">
        <?php if(empty($listaLibros)): ?>
            <p class="text-muted">No tienes libros en tu biblioteca.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach($listaLibros as $libro): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold"><?php echo htmlspecialchars($libro['titulo']); ?></h5>
                            <small class="text-muted">Comprado el: <?php echo date('d/m/Y', strtotime($libro['fecha_compra'])); ?></small>
                        </div>
                        <a href="../../uploads/libros/<?php echo $libro['archivo_pdf']; ?>" class="btn btn-sm btn-outline-success" target="_blank">
                            <i class="bi bi-download"></i> Descargar PDF
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
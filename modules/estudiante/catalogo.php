<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(3); // Solo Estudiantes
require_once '../../includes/header.php';

// 1. Obtener Cursos Disponibles
$cursos = $conexion->query("SELECT * FROM cursos ORDER BY id DESC")->fetchAll();

// 2. Obtener Libros Disponibles
$libros = $conexion->query("SELECT * FROM libros ORDER BY id DESC")->fetchAll();

// 3. Verificar qué ya compró el usuario (para no venderle lo mismo dos veces)
$mis_compras = $conexion->prepare("SELECT item_id, tipo_item FROM compras WHERE usuario_id = ?");
$mis_compras->execute([$_SESSION['usuario_id']]);
$comprados = $mis_compras->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
// $comprados tendrá estructura: [ID_ITEM => [tipo_item, ...]] (Simplificado para lógica visual)
?>

<div class="row mb-4 align-items-center">
    <div class="col">
        <h2 class="fw-bold text-dark"><i class="bi bi-shop"></i> Catálogo de Contenido</h2>
        <p class="text-muted">Explora y adquiere nuevo conocimiento.</p>
    </div>
</div>

<h4 class="text-primary mb-3"><i class="bi bi-laptop"></i> Cursos en Video</h4>
<?php if(empty($cursos)): ?>
    <div class="alert alert-info">No hay cursos disponibles por el momento.</div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <?php foreach($cursos as $c): ?>
            <?php 
                // Verificar si ya lo compró (Lógica simple)
                $yaComprado = false;
                // En un sistema real, haríamos una consulta específica combinada, 
                // aquí iteramos para el ejemplo visual.
                foreach($comprados as $compra) {
                    if($compra['item_id'] == $c['id'] && $compra['tipo_item'] == 'curso') $yaComprado = true;
                }
            ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-3">
                        <span class="badge bg-primary">Curso</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($c['titulo']); ?></h5>
                        <p class="card-text text-muted small"><?php echo htmlspecialchars($c['descripcion']); ?></p>
                        <h3 class="text-dark fw-bold">$<?php echo number_format($c['precio'], 0); ?></h3>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <?php if($yaComprado): ?>
                            <button class="btn btn-secondary w-100" disabled>Comprado</button>
                        <?php else: ?>
                            <a href="procesar_compra.php?tipo=curso&id=<?php echo $c['id']; ?>&precio=<?php echo $c['precio']; ?>" 
                               class="btn btn-outline-primary w-100" 
                               onclick="return confirm('¿Confirmar compra por $<?php echo $c['precio']; ?>?');">
                                <i class="bi bi-cart-plus"></i> Comprar Ahora
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h4 class="text-success mb-3"><i class="bi bi-book"></i> Libros Digitales</h4>
<?php if(empty($libros)): ?>
    <div class="alert alert-info">No hay libros disponibles por el momento.</div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach($libros as $l): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-3">
                        <span class="badge bg-success">Libro PDF</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($l['titulo']); ?></h5>
                        <p class="small text-muted mb-1">Autor: <?php echo htmlspecialchars($l['autor']); ?></p>
                        <h3 class="text-dark fw-bold">$<?php echo number_format($l['precio'], 0); ?></h3>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <a href="procesar_compra.php?tipo=libro&id=<?php echo $l['id']; ?>&precio=<?php echo $l['precio']; ?>" 
                           class="btn btn-outline-success w-100"
                           onclick="return confirm('¿Confirmar compra por $<?php echo $l['precio']; ?>?');">
                            <i class="bi bi-cart-plus"></i> Comprar PDF
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once '../../includes/footer.php'; ?>
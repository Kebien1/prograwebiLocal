<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(3); // Solo Estudiantes (ID 3)
require_once '../../includes/header.php';
?>

<div class="alert alert-primary border-0 shadow-sm mb-4" role="alert">
    <h4 class="alert-heading"><i class="bi bi-emoji-smile"></i> ¡Hola, <?php echo $_SESSION['nombre']; ?>!</h4>
    <p class="mb-0">Bienvenido a tu panel de estudiante. Tienes activo el <strong>Plan <?php echo $_SESSION['plan_nombre']; ?></strong>.</p>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-laptop display-1 text-primary opacity-50"></i>
                </div>
                <h3 class="card-title fw-bold">Mis Cursos</h3>
                <p class="card-text text-muted">Accede a las clases en video que has comprado.</p>
                <a href="#" class="btn btn-primary px-4">Ir al Aula Virtual</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-book display-1 text-success opacity-50"></i>
                </div>
                <h3 class="card-title fw-bold">Biblioteca</h3>
                <p class="card-text text-muted">Descarga tus libros y guías en PDF.</p>
                <a href="#" class="btn btn-success px-4">Ver Libros</a>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card bg-dark text-white border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center p-4">
                <div>
                    <h5 class="fw-bold mb-1">¿Necesitas más conocimiento?</h5>
                    <p class="mb-0 text-white-50">Explora nuestro catálogo completo de programación.</p>
                </div>
                <a href="#" class="btn btn-light fw-bold">Ver Catálogo</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
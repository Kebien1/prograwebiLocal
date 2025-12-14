<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(2); // Solo Docentes (ID 2)
require_once '../../includes/header.php';
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-success"><i class="bi bi-chalkboard"></i> Panel Académico</h2>
        <p class="text-muted">Gestiona tus cursos y materiales para los estudiantes.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-plus-circle-fill text-success display-4"></i>
                <h5 class="mt-3">Crear Curso</h5>
                <p class="text-muted small">Publica nuevo contenido.</p>
                <a href="#" class="btn btn-outline-success w-100">Nuevo Curso</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-people-fill text-success display-4"></i>
                <h5 class="mt-3">Mis Alumnos</h5>
                <p class="text-muted small">Ver listado de inscritos.</p>
                <a href="#" class="btn btn-outline-success w-100">Ver Lista</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-file-earmark-arrow-up-fill text-success display-4"></i>
                <h5 class="mt-3">Subir PDF</h5>
                <p class="text-muted small">Material de apoyo.</p>
                <a href="#" class="btn btn-outline-success w-100">Subir</a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
<?php
// Archivo: modules/docente/dashboard.php
include("../../config/bd.php");
include("../../includes/autenticacion.php");
include("../../includes/verificar_rol.php");

// Solo rol 3 (Docente)
verificarRol([3]);

include("../../includes/header.php");
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-success">Panel del Docente</h2>
        <p class="text-muted">Bienvenido, <?php echo $_SESSION['nick']; ?>. Aquí gestionas tus clases.</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="cursos/crear.php" class="btn btn-success fw-bold">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Curso
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm border-start border-success border-5">
            <div class="card-body p-4">
                <h3 class="fw-bold text-success mb-3"><i class="bi bi-journal-bookmark-fill me-2"></i>Mis Cursos</h3>
                <p class="text-muted">Ver listado de cursos que estás impartiendo, subir materiales y ver alumnos.</p>
                <a href="cursos/index.php" class="btn btn-success w-100">Ver mis cursos</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm border-start border-warning border-5">
            <div class="card-body p-4">
                <h3 class="fw-bold text-warning mb-3"><i class="bi bi-star-fill me-2"></i>Calificaciones</h3>
                <p class="text-muted">Evaluar entregas y asignar notas a los estudiantes.</p>
                <a href="calificaciones/index.php" class="btn btn-warning w-100 text-dark">Ir a Calificaciones</a>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
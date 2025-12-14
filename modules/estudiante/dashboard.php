<?php
// Archivo: modules/estudiante/dashboard.php
include("../../config/bd.php");
include("../../includes/autenticacion.php");
include("../../includes/verificar_rol.php");

// Solo rol 2 (Estudiante)
verificarRol([2]);

// Contar inscripciones
$idEst = $_SESSION['user_id'];
$sql = $conexion->prepare("SELECT COUNT(*) FROM inscripciones WHERE IdEstudiante = :id");
$sql->bindParam(":id", $idEst);
$sql->execute();
$misInscripciones = $sql->fetchColumn();

include("../../includes/header.php");
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-info text-dark">Panel del Estudiante</h2>
        <p class="text-muted">Hola, <?php echo $_SESSION['nick']; ?>. Continúa tu aprendizaje.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm bg-white hover-shadow">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-info">
                    <i class="bi bi-laptop display-4"></i>
                </div>
                <h4 class="fw-bold">Mis Clases</h4>
                <p class="text-muted small">Accede a tus <?php echo $misInscripciones; ?> cursos inscritos.</p>
                <a href="mis-cursos/index.php" class="btn btn-info text-white fw-bold w-100">Ir al Aula</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm bg-white hover-shadow">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-warning">
                    <i class="bi bi-star-fill display-4"></i>
                </div>
                <h4 class="fw-bold">Mis Notas</h4>
                <p class="text-muted small">Consulta tu rendimiento académico.</p>
                <a href="calificaciones/index.php" class="btn btn-warning w-100 text-dark fw-bold">Ver Boleta</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm bg-white hover-shadow">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-secondary">
                    <i class="bi bi-search display-4"></i>
                </div>
                <h4 class="fw-bold">Explorar</h4>
                <p class="text-muted small">Encuentra nuevos cursos e inscríbete.</p>
                <a href="cursos/index.php" class="btn btn-outline-secondary fw-bold w-100">Ver Catálogo</a>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
<?php
// Archivo: modules/estudiante/mis-cursos/index.php
include("../../../config/bd.php");
include("../../../includes/autenticacion.php");
include("../../../includes/verificar_rol.php");

verificarRol([2]); // Solo Estudiantes

$idEstudiante = $_SESSION['user_id'];

// Consulta JOIN para traer info del curso basado en la tabla de inscripciones
$sql = "SELECT c.*, i.FechaInscripcion, u.Nick as Docente
        FROM cursos c
        INNER JOIN inscripciones i ON c.ID = i.IdCurso
        INNER JOIN usuario u ON c.IdDocente = u.ID
        WHERE i.IdEstudiante = :id
        ORDER BY i.FechaInscripcion DESC";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(":id", $idEstudiante);
$stmt->execute();
$mis_clases = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../../../includes/header.php");
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-3">
        <h4 class="mb-0"><i class="bi bi-journal-bookmark-fill me-2"></i>Mis Clases Activas</h4>
    </div>
    <div class="card-body">
        
        <?php if(isset($_GET['mensaje'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_GET['mensaje']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(count($mis_clases) > 0): ?>
            <div class="list-group list-group-flush">
                <?php foreach($mis_clases as $clase): ?>
                <div class="list-group-item list-group-item-action p-4 border rounded mb-3 shadow-sm">
                    <div class="d-flex w-100 justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="mb-1 text-primary fw-bold"><?php echo $clase['Titulo']; ?></h4>
                            <p class="mb-1 text-muted">Profesor: <?php echo $clase['Docente']; ?></p>
                        </div>
                        <small class="text-muted">Inscrito desde: <?php echo date('d/m/Y', strtotime($clase['FechaInscripcion'])); ?></small>
                    </div>
                    <p class="mt-2 text-secondary"><?php echo $clase['Descripcion']; ?></p>
                    
                    <div class="mt-3">
                        <a href="../materiales/index.php?idCurso=<?php echo $clase['ID']; ?>" class="btn btn-success fw-bold px-4">
                            <i class="bi bi-folder2-open me-2"></i>Ver Materiales
                        </a>
                        </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-emoji-frown display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">No tienes cursos todavía.</h3>
                <a href="../cursos/index.php" class="btn btn-primary mt-3">Ir al Catálogo de Cursos</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include("../../../includes/footer.php"); ?>
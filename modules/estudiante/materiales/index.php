<?php
// Archivo: modules/estudiante/materiales/index.php
include("../../../config/bd.php");
include("../../../includes/autenticacion.php");
include("../../../includes/verificar_rol.php");

verificarRol([2]); // Solo Estudiantes

$idCurso = $_GET['idCurso'] ?? '';
$idEstudiante = $_SESSION['user_id'];

// 1. SEGURIDAD: Verificar si el estudiante está realmente inscrito en este curso
$check = $conexion->prepare("SELECT ID FROM inscripciones WHERE IdCurso=:c AND IdEstudiante=:e");
$check->bindParam(":c", $idCurso);
$check->bindParam(":e", $idEstudiante);
$check->execute();

if(!$check->fetch()){
    // Si no está inscrito, lo sacamos
    header("Location: ../cursos/index.php?mensaje=Debes inscribirte primero");
    exit;
}

// 2. Obtener info del curso
$stmtCurso = $conexion->prepare("SELECT Titulo FROM cursos WHERE ID = :id");
$stmtCurso->bindParam(":id", $idCurso);
$stmtCurso->execute();
$infoCurso = $stmtCurso->fetch(PDO::FETCH_ASSOC);

// 3. Obtener materiales
$stmtMat = $conexion->prepare("SELECT * FROM materiales WHERE IdCurso = :id ORDER BY ID DESC");
$stmtMat->bindParam(":id", $idCurso);
$stmtMat->execute();
$materiales = $stmtMat->fetchAll(PDO::FETCH_ASSOC);

include("../../../includes/header.php");
?>

<div class="row mb-3">
    <div class="col-12">
        <a href="../mis-cursos/index.php" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i> Volver a mis clases
        </a>
        <h2 class="mt-2 fw-bold text-success">
            Materiales: <span class="text-dark"><?php echo $infoCurso['Titulo']; ?></span>
        </h2>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nombre del Archivo</th>
                        <th>Fecha Publicación</th>
                        <th class="text-end pe-4">Descargar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($materiales) > 0): ?>
                        <?php foreach($materiales as $m): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="fs-4 text-danger me-3"><i class="bi bi-file-earmark-pdf"></i></div>
                                    <span class="fw-bold"><?php echo $m['Titulo']; ?></span>
                                </div>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($m['FechaSubida'])); ?></td>
                            <td class="text-end pe-4">
                                <a href="../../../uploads/<?php echo $m['Archivo']; ?>" target="_blank" class="btn btn-primary btn-sm px-3 rounded-pill">
                                    <i class="bi bi-download me-1"></i> Descargar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open fs-1 mb-2 d-block"></i>
                                El profesor aún no ha subido materiales para este curso.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../../includes/footer.php"); ?>
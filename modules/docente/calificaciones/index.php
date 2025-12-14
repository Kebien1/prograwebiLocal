<?php
// Archivo: modules/docente/calificaciones/index.php
include("../../../config/bd.php");
include("../../../includes/autenticacion.php");
include("../../../includes/verificar_rol.php");

verificarRol([3]); // Solo Docentes

$idDocente = $_SESSION['user_id'];
$idCurso = $_GET['idCurso'] ?? '';

// LOGICA 1: GUARDAR NOTA
if($_POST){
    $idInscripcion = $_POST['idInscripcion'];
    $nota = $_POST['nota'];
    $obs = $_POST['observacion'];
    $idCursoPost = $_POST['idCurso'];

    // Verificar si ya tiene calificación
    $check = $conexion->prepare("SELECT ID FROM calificaciones WHERE IdInscripcion = :id");
    $check->bindParam(":id", $idInscripcion);
    $check->execute();
    
    if($check->fetch()){
        // Actualizar
        $sql = "UPDATE calificaciones SET Nota=:n, Observacion=:o WHERE IdInscripcion=:id";
    } else {
        // Insertar
        $sql = "INSERT INTO calificaciones (IdInscripcion, Nota, Observacion) VALUES (:id, :n, :o)";
    }
    
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(":n", $nota);
    $stmt->bindParam(":o", $obs);
    $stmt->bindParam(":id", $idInscripcion);
    $stmt->execute();
    
    header("Location: index.php?idCurso=" . $idCursoPost . "&mensaje=Nota guardada");
    exit;
}

// LOGICA 2: OBTENER DATOS

// A) Lista de cursos del docente
$sqlCursos = $conexion->prepare("SELECT * FROM cursos WHERE IdDocente = :id");
$sqlCursos->bindParam(":id", $idDocente);
$sqlCursos->execute();
$mis_cursos = $sqlCursos->fetchAll(PDO::FETCH_ASSOC);

// B) Si seleccionó un curso, traer estudiantes
$estudiantes = [];
$cursoSeleccionado = null;

if($idCurso){
    // Verificar propiedad del curso
    foreach($mis_cursos as $c){
        if($c['ID'] == $idCurso) $cursoSeleccionado = $c;
    }

    if($cursoSeleccionado){
        // Traer inscripciones + datos usuario + calificaciones (LEFT JOIN)
        $sqlEst = "SELECT i.ID as IdInscripcion, u.Nick, u.Email, cal.Nota, cal.Observacion
                   FROM inscripciones i
                   INNER JOIN usuario u ON i.IdEstudiante = u.ID
                   LEFT JOIN calificaciones cal ON i.ID = cal.IdInscripcion
                   WHERE i.IdCurso = :idCurso";
        $stmtEst = $conexion->prepare($sqlEst);
        $stmtEst->bindParam(":idCurso", $idCurso);
        $stmtEst->execute();
        $estudiantes = $stmtEst->fetchAll(PDO::FETCH_ASSOC);
    }
}

include("../../../includes/header.php");
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-warning text-dark">Gestión de Calificaciones</h2>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body bg-light">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label fw-bold">Selecciona un Curso para calificar:</label>
                <select name="idCurso" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Seleccionar Curso --</option>
                    <?php foreach($mis_cursos as $c): ?>
                        <option value="<?php echo $c['ID']; ?>" <?php echo ($c['ID'] == $idCurso) ? 'selected' : ''; ?>>
                            <?php echo $c['Titulo']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <a href="../../dashboard.php" class="btn btn-outline-secondary w-100">Volver al Panel</a>
            </div>
        </form>
    </div>
</div>

<?php if($idCurso && $cursoSeleccionado): ?>
    
    <?php if(isset($_GET['mensaje'])): ?>
        <div class="alert alert-success"><?php echo $_GET['mensaje']; ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning">
            <h5 class="mb-0 text-dark">Estudiantes de: <strong><?php echo $cursoSeleccionado['Titulo']; ?></strong></h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Estudiante</th>
                            <th>Email</th>
                            <th style="width: 150px;">Nota (0-100)</th>
                            <th>Observación</th>
                            <th class="text-end pe-4">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($estudiantes) > 0): ?>
                            <?php foreach($estudiantes as $est): ?>
                            <form method="post">
                                <input type="hidden" name="idCurso" value="<?php echo $idCurso; ?>">
                                <input type="hidden" name="idInscripcion" value="<?php echo $est['IdInscripcion']; ?>">
                                <tr>
                                    <td class="ps-4 fw-bold"><?php echo $est['Nick']; ?></td>
                                    <td class="text-muted small"><?php echo $est['Email']; ?></td>
                                    <td>
                                        <input type="number" step="0.01" min="0" max="100" name="nota" 
                                               class="form-control form-control-sm border-warning" 
                                               value="<?php echo $est['Nota']; ?>" required>
                                    </td>
                                    <td>
                                        <input type="text" name="observacion" 
                                               class="form-control form-control-sm" 
                                               value="<?php echo $est['Observacion']; ?>" 
                                               placeholder="Comentario...">
                                    </td>
                                    <td class="text-end pe-4">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bi bi-save"></i> Guardar
                                        </button>
                                    </td>
                                </tr>
                            </form>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">No hay estudiantes inscritos en este curso.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include("../../../includes/footer.php"); ?>
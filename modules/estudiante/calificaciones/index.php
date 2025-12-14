<?php
// Archivo: modules/estudiante/calificaciones/index.php
include("../../../config/bd.php");
include("../../../includes/autenticacion.php");
include("../../../includes/verificar_rol.php");

verificarRol([2]); // Solo Estudiante

$idEstudiante = $_SESSION['user_id'];

// Consultar mis cursos y notas (LEFT JOIN a calificaciones)
$sql = "SELECT c.Titulo, u.Nick as Docente, cal.Nota, cal.Observacion, cal.FechaCalificacion
        FROM inscripciones i
        INNER JOIN cursos c ON i.IdCurso = c.ID
        INNER JOIN usuario u ON c.IdDocente = u.ID
        LEFT JOIN calificaciones cal ON i.ID = cal.IdInscripcion
        WHERE i.IdEstudiante = :id
        ORDER BY c.Titulo ASC";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(":id", $idEstudiante);
$stmt->execute();
$boleta = $stmt->fetchAll(PDO::FETCH_ASSOC);

include("../../../includes/header.php");
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-success">Mis Calificaciones</h2>
        <p class="text-muted">Reporte académico.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-success">
                    <tr>
                        <th class="ps-4">Curso</th>
                        <th>Docente</th>
                        <th class="text-center">Nota Final</th>
                        <th>Observaciones</th>
                        <th class="text-end pe-4">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($boleta) > 0): ?>
                        <?php foreach($boleta as $row): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?php echo $row['Titulo']; ?></td>
                            <td><?php echo $row['Docente']; ?></td>
                            <td class="text-center">
                                <?php if($row['Nota'] !== null): ?>
                                    <?php if($row['Nota'] >= 60): // Suponiendo 60 nota aprobación ?>
                                        <span class="badge bg-success fs-6"><?php echo $row['Nota']; ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger fs-6"><?php echo $row['Nota']; ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Sin calificar</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small">
                                <?php echo $row['Observacion'] ? $row['Observacion'] : '-'; ?>
                            </td>
                            <td class="text-end pe-4 text-muted small">
                                <?php echo $row['FechaCalificacion'] ? date('d/m/Y', strtotime($row['FechaCalificacion'])) : '-'; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                Aún no estás inscrito en ningún curso.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 text-center">
    <a href="../dashboard.php" class="btn btn-outline-secondary">Volver al Dashboard</a>
</div>

<?php include("../../../includes/footer.php"); ?>
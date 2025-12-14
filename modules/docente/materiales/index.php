<?php
// Archivo: modules/docente/materiales/index.php
include("../../../config/bd.php");
include("../../../includes/autenticacion.php");
include("../../../includes/verificar_rol.php");

verificarRol([3]); // Solo Docentes

$idCurso = $_GET['idCurso'] ?? '';
$idDocente = $_SESSION['user_id'];

// 1. Verificar seguridad: ¿El curso pertenece a este docente?
$sqlCurso = $conexion->prepare("SELECT Titulo FROM cursos WHERE ID = :id AND IdDocente = :docente");
$sqlCurso->bindParam(":id", $idCurso);
$sqlCurso->bindParam(":docente", $idDocente);
$sqlCurso->execute();
$curso = $sqlCurso->fetch(PDO::FETCH_ASSOC);

if(!$curso){
    header("Location: ../cursos/index.php?mensaje=Acceso no autorizado al curso");
    exit;
}

// 2. Eliminar material (si se solicita)
if(isset($_GET['borrar'])){
    $idMaterial = $_GET['borrar'];
    
    // Obtener nombre del archivo para borrarlo de la carpeta también
    $sqlFile = $conexion->prepare("SELECT Archivo FROM materiales WHERE ID = :id");
    $sqlFile->bindParam(":id", $idMaterial);
    $sqlFile->execute();
    $fichero = $sqlFile->fetchColumn();

    if($fichero && file_exists("../../../uploads/" . $fichero)){
        unlink("../../../uploads/" . $fichero); // Borrar físico
    }

    $borrar = $conexion->prepare("DELETE FROM materiales WHERE ID = :id");
    $borrar->bindParam(":id", $idMaterial);
    $borrar->execute();
    header("Location: index.php?idCurso=$idCurso&mensaje=Material eliminado");
    exit;
}

// 3. Listar materiales del curso
$sqlMat = $conexion->prepare("SELECT * FROM materiales WHERE IdCurso = :id ORDER BY ID DESC");
$sqlMat->bindParam(":id", $idCurso);
$sqlMat->execute();
$materiales = $sqlMat->fetchAll(PDO::FETCH_ASSOC);

include("../../../includes/header.php");
?>

<div class="row mb-3">
    <div class="col-md-8">
        <h4 class="text-success fw-bold">
            <a href="../cursos/index.php" class="text-decoration-none text-muted fs-5 me-2"><i class="bi bi-arrow-left"></i></a>
            Materiales: <span class="text-dark"><?php echo $curso['Titulo']; ?></span>
        </h4>
    </div>
    <div class="col-md-4 text-end">
        <a href="subir.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-primary fw-bold">
            <i class="bi bi-cloud-upload me-2"></i>Subir Archivo
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <?php if(isset($_GET['mensaje'])): ?>
            <div class="alert alert-success m-3 alert-dismissible fade show">
                <?php echo $_GET['mensaje']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Archivo</th>
                        <th>Fecha de Subida</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($materiales) > 0): ?>
                        <?php foreach($materiales as $m): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="fs-4 text-danger me-3"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                                    <div>
                                        <span class="fw-bold d-block"><?php echo $m['Titulo']; ?></span>
                                        <small class="text-muted"><?php echo $m['Archivo']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($m['FechaSubida'])); ?></td>
                            <td class="text-end pe-4">
                                <a href="../../../uploads/<?php echo $m['Archivo']; ?>" target="_blank" class="btn btn-sm btn-outline-info" title="Descargar">
                                    <i class="bi bi-download"></i>
                                </a>
                                <a href="index.php?idCurso=<?php echo $idCurso; ?>&borrar=<?php echo $m['ID']; ?>" onclick="return confirm('¿Borrar archivo permanentemente?')" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open fs-1 d-block mb-2"></i>
                                No hay materiales subidos en este curso.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../../includes/footer.php"); ?>
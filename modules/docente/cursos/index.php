<?php
// Archivo: modules/docente/cursos/index.php
include("../../../config/bd.php");
include("../../../includes/autenticacion.php");
include("../../../includes/verificar_rol.php");

// 1. Verificar que sea Docente (Rol 3)
verificarRol([3]);

$idDocente = $_SESSION['user_id'];

// 2. Consulta para traer SOLO los cursos de este docente
$sentencia = $conexion->prepare("SELECT * FROM cursos WHERE IdDocente = :id ORDER BY ID DESC");
$sentencia->bindParam(":id", $idDocente);
$sentencia->execute();
$mis_cursos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../../includes/header.php");
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold text-success"><i class="bi bi-mortarboard-fill me-2"></i>Mis Cursos</h4>
        <a href="crear.php" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Nuevo Curso</a>
    </div>
    
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
                        <th class="ps-4">ID</th>
                        <th>Título del Curso</th>
                        <th>Fecha Creación</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($mis_cursos) > 0): ?>
                        <?php foreach($mis_cursos as $curso): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?php echo $curso['ID']; ?></td>
                            <td>
                                <span class="fw-bold d-block"><?php echo $curso['Titulo']; ?></span>
                                <small class="text-muted"><?php echo substr($curso['Descripcion'], 0, 50); ?>...</small>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($curso['FechaCreacion'])); ?></td>
                            <td>
                                <?php if($curso['Estado'] == 1): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Oculto</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="../materiales/index.php?idCurso=<?php echo $curso['ID']; ?>" class="btn btn-sm btn-outline-primary" title="Subir Materiales">
                                        <i class="bi bi-folder-fill"></i>
                                    </a>
                                    <a href="editar.php?id=<?php echo $curso['ID']; ?>" class="btn btn-sm btn-outline-warning" title="Editar Curso">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No has creado ningún curso todavía.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../../includes/footer.php"); ?>
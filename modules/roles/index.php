<?php 
// Archivo: modules/roles/index.php
include("../../includes/autenticacion.php");
include("../../config/bd.php");

// 1. Eliminar Rol
if(isset($_GET['txtID'])){
    $txtID = $_GET['txtID'];
    $sentencia = $conexion->prepare("DELETE FROM rol WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    header("Location: index.php?mensaje=Rol eliminado");
    exit;
}

// 2. Listar Roles
$sentencia = $conexion->prepare("SELECT * FROM rol");
$sentencia->execute();
$lista_roles = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../includes/header.php"); 
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2"></i>Roles del Sistema</h5>
        <a class="btn btn-primary" href="crear.php"><i class="bi bi-plus-lg me-1"></i>Nuevo Rol</a>
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
                        <th>Descripción</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_roles as $rol): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#<?php echo $rol['ID']; ?></td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-dark fs-6">
                                <?php echo $rol['Descrip']; ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="permisos.php?idRol=<?php echo $rol['ID']; ?>" class="btn btn-sm btn-outline-info" title="Asignar Permisos">
                                    <i class="bi bi-key-fill"></i> Permisos
                                </a>
                                <a href="editar.php?txtID=<?php echo $rol['ID']; ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="index.php?txtID=<?php echo $rol['ID']; ?>" onclick="return confirm('¿Borrar este rol?');" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
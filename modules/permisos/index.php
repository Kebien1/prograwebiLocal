<?php 
// Archivo: modules/permisos/index.php
include("../../includes/autenticacion.php");
include("../../config/bd.php");

// Eliminar
if(isset($_GET['txtID'])){
    $txtID = $_GET['txtID'];
    $sentencia = $conexion->prepare("DELETE FROM permisos WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    header("Location: index.php?mensaje=Permiso eliminado");
    exit;
}

// Listar
$sentencia = $conexion->prepare("SELECT * FROM permisos");
$sentencia->execute();
$lista_permisos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../includes/header.php"); 
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-key me-2"></i>Catálogo de Permisos</h5>
        <a class="btn btn-primary" href="crear.php"><i class="bi bi-plus-lg me-1"></i>Nuevo Permiso</a>
    </div>
    <div class="card-body p-0">
        <?php if(isset($_GET['mensaje'])): ?>
            <div class="alert alert-success m-3 alert-dismissible fade show">
                <?php echo $_GET['mensaje']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Descripción del Permiso</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_permisos as $p): ?>
                    <tr>
                        <td class="ps-4 fw-bold">#<?php echo $p['ID']; ?></td>
                        <td><?php echo $p['Descrip']; ?></td>
                        <td class="text-end pe-4">
                            <a href="editar.php?txtID=<?php echo $p['ID']; ?>" class="btn btn-sm btn-outline-warning">Editar</a>
                            <a href="index.php?txtID=<?php echo $p['ID']; ?>" onclick="return confirm('¿Borrar permiso?')" class="btn btn-sm btn-outline-danger">Borrar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
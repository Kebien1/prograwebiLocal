<?php 
// Archivo: modules/usuarios/index.php
include("../../includes/autenticacion.php");
include("../../config/bd.php");

// 1. Lógica para ELIMINAR usuario
if(isset($_GET['txtID'])){
    $txtID = $_GET['txtID'];
    // Preparamos la sentencia para borrar
    $sentencia = $conexion->prepare("DELETE FROM usuario WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    header("Location: index.php?mensaje=Usuario eliminado correctamente");
    exit;
}

// 2. Lógica para LISTAR usuarios (con el nombre del Rol)
// Usamos un LEFT JOIN para traer el nombre del rol en lugar de solo el ID
$sentencia = $conexion->prepare("SELECT u.*, r.Descrip as RolNombre FROM usuario u LEFT JOIN rol r ON u.IdRol = r.ID");
$sentencia->execute();
$lista_usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../includes/header.php"); 
?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2"></i>Gestión de Usuarios</h5>
        <a class="btn btn-primary" href="crear.php"><i class="bi bi-plus-lg me-1"></i>Nuevo Usuario</a>
    </div>
    
    <div class="card-body p-0">
        <?php if(isset($_GET['mensaje'])) { ?>
            <div class="alert alert-success m-3 alert-dismissible fade show">
                <?php echo $_GET['mensaje']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_usuarios as $registro) { ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#<?php echo $registro['ID']; ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-2 text-primary">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <strong><?php echo $registro['Nick']; ?></strong>
                            </div>
                        </td>
                        <td><?php echo $registro['Email']; ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo $registro['RolNombre'] ?? 'Sin Rol'; ?></span></td>
                        <td>
                            <?php if($registro['Estado'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="editar.php?txtID=<?php echo $registro['ID']; ?>" class="btn btn-sm btn-outline-warning me-1" title="Editar">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <a href="index.php?txtID=<?php echo $registro['ID']; ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?');" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
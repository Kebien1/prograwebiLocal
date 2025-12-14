<?php 
// Archivo: modules/roles/editar.php
include("../../../includes/autenticacion.php");
include("../../../config/bd.php"); 

if(isset($_GET["txtID"])){
    $txtID = $_GET["txtID"];
    $sentencia = $conexion->prepare("SELECT * FROM rol WHERE ID = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $rol = $sentencia->fetch(PDO::FETCH_LAZY);
    if(!$rol) header("Location: index.php");
}

if($_POST){
    $txtID = $_POST["ID"];
    $descrip = $_POST["Descrip"];
    $sentencia = $conexion->prepare("UPDATE rol SET Descrip=:d WHERE ID=:id");
    $sentencia->bindParam(":d", $descrip);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    header("Location: index.php?mensaje=Rol actualizado");
    exit;
}
include("../../../includes/header.php"); 
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-warning bg-opacity-25">
                <h5 class="mb-0">Editar Rol</h5>
            </div>
            <div class="card-body p-4">
                <form method="post">
                    <input type="hidden" name="ID" value="<?php echo $txtID; ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Rol</label>
                        <input type="text" class="form-control" name="Descrip" value="<?php echo $rol['Descrip']; ?>" required>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../../../includes/footer.php"); ?>
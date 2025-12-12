<?php 
// Archivo: modules/permisos/crear.php
include("../../includes/autenticacion.php");
include("../../config/bd.php"); 

if($_POST){
    $descrip = $_POST["Descrip"];
    if($descrip){
        $sentencia = $conexion->prepare("INSERT INTO permisos(Descrip) VALUES (:d)");
        $sentencia->bindParam(":d", $descrip);
        $sentencia->execute();
        header("Location: index.php?mensaje=Permiso creado");
        exit;
    }
}
include("../../includes/header.php"); 
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Nuevo Permiso</div>
            <div class="card-body p-4">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción</label>
                        <input type="text" class="form-control" name="Descrip" placeholder="Ej: Eliminar Usuarios" required>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
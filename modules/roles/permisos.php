<?php 
// Archivo: modules/roles/permisos.php
include("../../includes/autenticacion.php");
include("../../config/bd.php");

$idRol = isset($_GET['idRol']) ? $_GET['idRol'] : "";

// Obtener roles
$sqlRoles = $conexion->prepare("SELECT * FROM rol");
$sqlRoles->execute();
$todos_roles = $sqlRoles->fetchAll(PDO::FETCH_ASSOC);

if(empty($idRol) && count($todos_roles) > 0) {
    $idRol = $todos_roles[0]['ID'];
}

// Procesar Guardado
if($_POST){
    $idRol = $_POST['idRol'];
    $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : []; 

    $borrar = $conexion->prepare("DELETE FROM detalles WHERE IdRol = :r");
    $borrar->bindParam(":r", $idRol);
    $borrar->execute();

    foreach($permisos as $idPermiso){
        $insertar = $conexion->prepare("INSERT INTO detalles (IdRol, IdPermiso) VALUES (:r, :p)");
        $insertar->bindParam(":r", $idRol);
        $insertar->bindParam(":p", $idPermiso);
        $insertar->execute();
    }
    $mensaje = "Permisos actualizados correctamente.";
}

// Obtener permisos
$sqlPermisos = $conexion->prepare("SELECT * FROM permisos");
$sqlPermisos->execute();
$todos_permisos = $sqlPermisos->fetchAll(PDO::FETCH_ASSOC);

// Obtener asignados
$permisos_asignados = [];
if(!empty($idRol)){
    $sqlAsignados = $conexion->prepare("SELECT IdPermiso FROM detalles WHERE IdRol = :r");
    $sqlAsignados->bindParam(":r", $idRol);
    $sqlAsignados->execute();
    $permisos_asignados = $sqlAsignados->fetchAll(PDO::FETCH_COLUMN, 0);
}

include("../../includes/header.php"); 
?>

<div class="card shadow-sm mb-5">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Asignar Permisos a Roles</h5>
    </div>
    <div class="card-body">
        
        <?php if(isset($mensaje)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="" method="get" class="mb-4 bg-light p-3 rounded border">
            <label class="form-label fw-bold">Selecciona el Rol a configurar:</label>
            <div class="input-group">
                <select name="idRol" class="form-select" onchange="this.form.submit()">
                    <?php foreach($todos_roles as $r): ?>
                        <option value="<?php echo $r['ID']; ?>" <?php echo ($r['ID'] == $idRol) ? 'selected' : ''; ?>>
                            <?php echo $r['Descrip']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-secondary" type="submit">Cargar</button>
            </div>
        </form>

        <hr>

        <?php if(!empty($idRol)): ?>
        <form action="" method="post">
            <input type="hidden" name="idRol" value="<?php echo $idRol; ?>">
            <h6 class="mb-3 fw-bold text-secondary">Marca los permisos que tendrá este rol:</h6>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <?php foreach($todos_permisos as $p): 
                    $marcado = in_array($p['ID'], $permisos_asignados) ? "checked" : "";
                ?>
                <div class="col">
                    <div class="form-check p-3 border rounded bg-white h-100 shadow-sm position-relative">
                        <input class="form-check-input" type="checkbox" name="permisos[]" 
                               value="<?php echo $p['ID']; ?>" id="p_<?php echo $p['ID']; ?>" 
                               <?php echo $marcado; ?>>
                        <label class="form-check-label w-100 stretched-link" for="p_<?php echo $p['ID']; ?>">
                            <?php echo $p['Descrip']; ?>
                        </label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-4 border-top pt-3 text-end">
                <a href="index.php" class="btn btn-secondary me-2 position-relative" style="z-index: 2;">Volver</a>
                <button type="submit" class="btn btn-success btn-lg px-5 position-relative" style="z-index: 2;">
                    <i class="bi bi-save me-2"></i>Guardar Cambios
                </button>
            </div>
        </form>
        <?php endif; ?>

    </div>
</div>

<?php include("../../includes/footer.php"); ?>
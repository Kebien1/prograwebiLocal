<?php 
// Archivo: modules/usuarios/editar.php
include("../../includes/autenticacion.php");
include("../../config/bd.php"); 

// 1. Obtener datos del usuario a editar
if(isset($_GET["txtID"])){
    $txtID = $_GET["txtID"];
    $sentencia = $conexion->prepare("SELECT * FROM usuario WHERE ID = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);
    
    if(!$usuario) { header("Location: index.php"); exit; }
}

// 2. Obtener roles
$stmt = $conexion->prepare("SELECT * FROM rol");
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Procesar formulario de actualización
if($_POST){
    $txtID   = $_POST["ID"];
    $nick    = $_POST["Nick"];
    $email   = $_POST["Email"];
    $idRol   = $_POST["IdRol"];
    $estado  = $_POST["Estado"];
    $pass    = $_POST["Password"];

    // Si escribe contraseña nueva, la actualizamos encriptada. Si no, dejamos la vieja.
    if(!empty($pass)){
        $passHash = password_hash($pass, PASSWORD_BCRYPT);
        $sql = "UPDATE usuario SET Nick=:n, Email=:e, IdRol=:r, Estado=:s, Password=:p WHERE ID=:id";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(":p", $passHash);
    } else {
        $sql = "UPDATE usuario SET Nick=:n, Email=:e, IdRol=:r, Estado=:s WHERE ID=:id";
        $sentencia = $conexion->prepare($sql);
    }

    $sentencia->bindParam(":n", $nick);
    $sentencia->bindParam(":e", $email);
    $sentencia->bindParam(":r", $idRol);
    $sentencia->bindParam(":s", $estado);
    $sentencia->bindParam(":id", $txtID);
    
    if($sentencia->execute()){
        header("Location: index.php?mensaje=Usuario actualizado correctamente");
        exit;
    }
}

include("../../includes/header.php");
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning bg-opacity-25">
                <h5 class="mb-0 text-dark">Editar Usuario #<?php echo $usuario['ID']; ?></h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="post">
                    <input type="hidden" name="ID" value="<?php echo $usuario['ID']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Usuario</label>
                        <input type="text" class="form-control" name="Nick" value="<?php echo $usuario['Nick']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" name="Email" value="<?php echo $usuario['Email']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Contraseña</label>
                        <input type="password" class="form-control" name="Password" placeholder="Dejar vacío para no cambiar">
                        <small class="text-muted">Solo escribe si quieres cambiarla</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Rol</label>
                            <select name="IdRol" class="form-select">
                                <?php foreach($roles as $rol): ?>
                                    <option value="<?php echo $rol['ID']; ?>" <?php echo ($rol['ID'] == $usuario['IdRol']) ? 'selected' : ''; ?>>
                                        <?php echo $rol['Descrip']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="Estado" class="form-select">
                                <option value="1" <?php echo ($usuario['Estado'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                <option value="0" <?php echo ($usuario['Estado'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">Actualizar Datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
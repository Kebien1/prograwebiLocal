<?php 
// Archivo: modules/usuarios/crear.php
include("../../../includes/autenticacion.php");
include("../../../config/bd.php"); 

// Obtener roles para el select
$stmt = $conexion->prepare("SELECT * FROM rol");
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($_POST){
    $usuario = $_POST["Nick"];
    $clave   = $_POST["Password"];
    $email   = $_POST["Email"];
    $idRol   = $_POST["IdRol"];
    $estado  = $_POST["Estado"];

    // Encriptamos la contraseña para seguridad
    $claveHash = password_hash($clave, PASSWORD_BCRYPT);

    // Insertamos usuario (verificado por defecto si lo crea un admin)
    $sentencia = $conexion->prepare("INSERT INTO usuario(Nick, Password, Email, Estado, IdRol, Verificado) VALUES (:n, :p, :e, :s, :r, 1)");
    $sentencia->bindParam(":n", $usuario);
    $sentencia->bindParam(":p", $claveHash);
    $sentencia->bindParam(":e", $email);
    $sentencia->bindParam(":s", $estado);
    $sentencia->bindParam(":r", $idRol);
    
    if($sentencia->execute()){
        header("Location: index.php?mensaje=Usuario creado exitosamente");
        exit;
    }
}

include("../../../includes/header.php");
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Nuevo Usuario</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre de Usuario</label>
                        <input type="text" class="form-control" name="Nick" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" class="form-control" name="Email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contraseña</label>
                        <input type="password" class="form-control" name="Password" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Rol</label>
                            <select name="IdRol" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <?php foreach($roles as $rol): ?>
                                    <option value="<?php echo $rol['ID']; ?>"><?php echo $rol['Descrip']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="Estado" class="form-select" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success px-4">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../../../includes/footer.php"); ?>
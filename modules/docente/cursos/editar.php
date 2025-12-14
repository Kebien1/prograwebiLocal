<?php
// Archivo: modules/docente/cursos/editar.php
include("../../../config/bd.php");
include("../../../includes/autenticacion.php");
include("../../../includes/verificar_rol.php");

verificarRol([3]);

$idCurso = $_GET['id'] ?? '';
$idDocente = $_SESSION['user_id'];

// 1. Buscar datos del curso Y verificar que pertenezca al docente
$sql = $conexion->prepare("SELECT * FROM cursos WHERE ID = :id AND IdDocente = :docente");
$sql->bindParam(":id", $idCurso);
$sql->bindParam(":docente", $idDocente);
$sql->execute();
$curso = $sql->fetch(PDO::FETCH_ASSOC);

// Si no existe o no es suyo, lo sacamos
if(!$curso){
    header("Location: index.php");
    exit;
}

// 2. Actualizar datos
if($_POST){
    $titulo = $_POST['Titulo'];
    $descripcion = $_POST['Descripcion'];
    $estado = $_POST['Estado'];

    $update = $conexion->prepare("UPDATE cursos SET Titulo=:t, Descripcion=:d, Estado=:e WHERE ID=:id");
    $update->bindParam(":t", $titulo);
    $update->bindParam(":d", $descripcion);
    $update->bindParam(":e", $estado);
    $update->bindParam(":id", $idCurso);

    if($update->execute()){
        header("Location: index.php?mensaje=Curso actualizado correctamente");
        exit;
    }
}

include("../../../includes/header.php");
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning bg-opacity-25">
                <h5 class="mb-0 text-dark">Editar Curso</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título</label>
                        <input type="text" name="Titulo" class="form-control" value="<?php echo $curso['Titulo']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="Descripcion" class="form-control" rows="5" required><?php echo $curso['Descripcion']; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Estado</label>
                        <select name="Estado" class="form-select">
                            <option value="1" <?php echo ($curso['Estado'] == 1) ? 'selected' : ''; ?>>Activo (Visible)</option>
                            <option value="0" <?php echo ($curso['Estado'] == 0) ? 'selected' : ''; ?>>Oculto (No visible)</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">Actualizar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../../../includes/footer.php"); ?>
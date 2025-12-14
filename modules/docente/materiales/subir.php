<?php
// Archivo: modules/docente/materiales/subir.php
include("../../../config/bd.php");
include("../../../includes/autenticacion.php");
include("../../../includes/verificar_rol.php");

verificarRol([3]);

$idCurso = $_GET['idCurso'] ?? $_POST['idCurso'] ?? '';
$idDocente = $_SESSION['user_id'];

// 1. Verificar seguridad (otra vez)
$sqlCurso = $conexion->prepare("SELECT Titulo FROM cursos WHERE ID = :id AND IdDocente = :docente");
$sqlCurso->bindParam(":id", $idCurso);
$sqlCurso->bindParam(":docente", $idDocente);
$sqlCurso->execute();
if(!$sqlCurso->fetch()){
    header("Location: ../cursos/index.php");
    exit;
}

// 2. Procesar subida
if($_POST && isset($_FILES['archivo'])){
    $titulo = $_POST['Titulo'];
    
    // Manejo del archivo
    $nombreArchivo = $_FILES['archivo']['name'];
    $tmpArchivo    = $_FILES['archivo']['tmp_name'];
    
    // Generar nombre único para evitar duplicados: fecha_nombre
    $nombreFinal = date("YmdHis") . "_" . $nombreArchivo;
    $rutaDestino = "../../../uploads/" . $nombreFinal;

    if(move_uploaded_file($tmpArchivo, $rutaDestino)){
        $sql = "INSERT INTO materiales (IdCurso, Titulo, Archivo, Tipo) VALUES (:c, :t, :a, 'Archivo')";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":c", $idCurso);
        $stmt->bindParam(":t", $titulo);
        $stmt->bindParam(":a", $nombreFinal);
        
        if($stmt->execute()){
            header("Location: index.php?idCurso=$idCurso&mensaje=Archivo subido con éxito");
            exit;
        }
    } else {
        $error = "Error al mover el archivo a la carpeta uploads.";
    }
}

include("../../../includes/header.php");
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Subir Material</h5>
            </div>
            <div class="card-body p-4">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="idCurso" value="<?php echo $idCurso; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título del Material</label>
                        <input type="text" name="Titulo" class="form-control" placeholder="Ej: Diapositivas Clase 1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Seleccionar Archivo</label>
                        <input type="file" name="archivo" class="form-control" required>
                        <small class="text-muted">PDF, Word, Imágenes, ZIP.</small>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="index.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">Subir Ahora</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../../../includes/footer.php"); ?>
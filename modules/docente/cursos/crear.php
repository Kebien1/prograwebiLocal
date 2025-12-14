<?php
// Archivo: modules/docente/cursos/crear.php
include("../../../config/bd.php");
include("../../../includes/autenticacion.php");
include("../../../includes/verificar_rol.php");

verificarRol([3]); // Solo Docentes

if($_POST){
    $titulo = $_POST['Titulo'];
    $descripcion = $_POST['Descripcion'];
    $idDocente = $_SESSION['user_id']; // El ID del usuario logueado

    if($titulo && $descripcion){
        $sql = "INSERT INTO cursos (Titulo, Descripcion, IdDocente, Estado) VALUES (:t, :d, :id, 1)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":t", $titulo);
        $stmt->bindParam(":d", $descripcion);
        $stmt->bindParam(":id", $idDocente);
        
        if($stmt->execute()){
            header("Location: index.php?mensaje=Curso creado exitosamente");
            exit;
        }
    }
}

include("../../../includes/header.php");
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Crear Nuevo Curso</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título del Curso</label>
                        <input type="text" name="Titulo" class="form-control" placeholder="Ej: Matemáticas I" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="Descripcion" class="form-control" rows="5" placeholder="¿De qué trata este curso?" required></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success px-4">Guardar Curso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../../../includes/footer.php"); ?>
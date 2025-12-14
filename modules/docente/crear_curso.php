<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(2); // Solo Docente

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $desc = trim($_POST['descripcion']);
    $precio = $_POST['precio'];
    $docente_id = $_SESSION['usuario_id'];

    if($titulo && $precio >= 0) {
        // En tu DB tienes fecha_creacion, así que usamos NOW() en SQL
        // Si no tienes esa columna en la tabla cursos, borra ", fecha_creacion" y ", NOW()"
        $sql = "INSERT INTO cursos (titulo, descripcion, precio, docente_id, fecha_creacion) VALUES (?, ?, ?, ?, NOW())";
        
        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$titulo, $desc, $precio, $docente_id]);
            header("Location: mis_cursos.php");
            exit;
        } catch (PDOException $e) {
            $mensaje = "<div class='alert alert-danger'>Error al guardar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning'>Por favor revisa los datos. El precio no puede ser negativo.</div>";
    }
}

require_once '../../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white py-3 border-bottom-0">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle"></i> Publicar Nuevo Curso</h4>
                </div>
                <div class="card-body p-5">
                    
                    <?php echo $mensaje; ?>

                    <form method="post">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Título del Curso</label>
                            <input type="text" name="titulo" class="form-control form-control-lg" placeholder="Ej: Introducción a Python" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Descripción Detallada</label>
                            <textarea name="descripcion" class="form-control" rows="5" placeholder="¿Qué aprenderán los alumnos en este curso?"></textarea>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Precio ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">$</span>
                                    <input type="number" name="precio" class="form-control" placeholder="0.00" min="0" step="0.01" required>
                                </div>
                                <div class="form-text">Ingresa 0 para cursos gratuitos.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Imagen de Portada</label>
                                <input type="file" class="form-control" disabled title="Funcionalidad disponible en versión Pro">
                                <div class="form-text text-muted">La subida de imágenes estará disponible pronto.</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="mis_cursos.php" class="btn btn-light btn-lg px-4">Cancelar</a>
                            <button type="submit" class="btn btn-success btn-lg px-5 fw-bold">Publicar Curso</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
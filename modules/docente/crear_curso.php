<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(2); // Solo Docente

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $desc = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $docente_id = $_SESSION['usuario_id'];

    if($titulo && $precio) {
        $sql = "INSERT INTO cursos (titulo, descripcion, precio, docente_id) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$titulo, $desc, $precio, $docente_id]);
        
        header("Location: mis_cursos.php");
        exit;
    }
}

require_once '../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white fw-bold">Publicar Nuevo Curso</div>
            <div class="card-body p-4">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título del Curso</label>
                        <input type="text" name="titulo" class="form-control form-control-lg" placeholder="Ej: Introducción a PHP" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="¿Qué aprenderán los alumnos?"></textarea>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Precio ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Portada (Opcional)</label>
                            <input type="file" class="form-control" disabled title="Funcionalidad próxima">
                            <div class="form-text">La subida de imágenes estará disponible pronto.</div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">Publicar Curso</button>
                        <a href="mis_cursos.php" class="btn btn-light text-muted">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(1);

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $precio = $_POST['precio'];
    $limite = $_POST['limite_sesiones'];
    $desc = $_POST['descripcion'];

    $sql = "UPDATE planes SET precio=?, limite_sesiones=?, descripcion=? WHERE id=?";
    $conexion->prepare($sql)->execute([$precio, $limite, $desc, $id]);
    
    header("Location: planes.php");
    exit;
}

$plan = $conexion->prepare("SELECT * FROM planes WHERE id = ?");
$plan->execute([$id]);
$p = $plan->fetch();

require_once '../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white fw-bold">Editar Plan: <?php echo $p['nombre']; ?></div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Precio Mensual ($)</label>
                        <input type="number" step="0.01" name="precio" class="form-control" value="<?php echo $p['precio']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Límite de Sesiones</label>
                        <input type="number" name="limite_sesiones" class="form-control" value="<?php echo $p['limite_sesiones']; ?>" required min="1">
                        <div class="form-text">Número máximo de dispositivos simultáneos permitidos.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($p['descripcion']); ?></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Actualizar Plan</button>
                        <a href="planes.php" class="btn btn-light text-muted">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
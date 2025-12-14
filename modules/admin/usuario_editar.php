<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(1); // Solo Admin

$id = $_GET['id'] ?? 0;
$mensaje = "";

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol_id'];
    $plan = $_POST['plan_id'];
    $estado = $_POST['estado'];

    $sql = "UPDATE usuarios SET nombre_completo=?, email=?, rol_id=?, plan_id=?, estado=? WHERE id=?";
    $conexion->prepare($sql)->execute([$nombre, $email, $rol, $plan, $estado, $id]);
    
    header("Location: usuarios.php"); // Volver a la lista
    exit;
}

// Obtener datos del usuario
$usuario = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$usuario->execute([$id]);
$u = $usuario->fetch();

if (!$u) die("Usuario no encontrado");

// Obtener listas para los select
$roles = $conexion->query("SELECT * FROM roles")->fetchAll();
$planes = $conexion->query("SELECT * FROM planes")->fetchAll();

require_once '../../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow border-0">
            <div class="card-header bg-white fw-bold">Editar Usuario</div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($u['nombre_completo']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($u['email']); ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rol</label>
                            <select name="rol_id" class="form-select">
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?php echo $r['id']; ?>" <?php echo ($u['rol_id'] == $r['id']) ? 'selected' : ''; ?>>
                                        <?php echo $r['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plan de Suscripción</label>
                            <select name="plan_id" class="form-select">
                                <?php foreach ($planes as $p): ?>
                                    <option value="<?php echo $p['id']; ?>" <?php echo ($u['plan_id'] == $p['id']) ? 'selected' : ''; ?>>
                                        <?php echo $p['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="1" <?php echo ($u['estado'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($u['estado'] == 0) ? 'selected' : ''; ?>>Bloqueado</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="usuarios.php" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
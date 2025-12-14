<?php
require_once '../../config/bd.php';
require_once '../../includes/security.php';
verificarRol(1); // Solo Admin
require_once '../../includes/header.php';

// Eliminar usuario si se solicita
if (isset($_GET['borrar'])) {
    $id = $_GET['borrar'];
    // Evitar que el admin se borre a sí mismo
    if ($id != $_SESSION['usuario_id']) {
        $conexion->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);
        echo "<script>window.location='usuarios.php';</script>";
    }
}

// Consultar usuarios con sus roles y planes
$sql = "SELECT u.*, r.nombre as rol, p.nombre as plan 
        FROM usuarios u 
        JOIN roles r ON u.rol_id = r.id 
        JOIN planes p ON u.plan_id = p.id 
        ORDER BY u.id DESC";
$usuarios = $conexion->query($sql)->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark"><i class="bi bi-people-fill"></i> Gestión de Usuarios</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Usuario</th>
                        <th>Rol</th>
                        <th>Plan Actual</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold"><?php echo htmlspecialchars($u['nombre_completo']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($u['email']); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo $u['rol']; ?></span>
                        </td>
                        <td>
                            <?php 
                                $badgeColor = ($u['plan'] == 'Premium') ? 'bg-warning text-dark' : (($u['plan'] == 'Pro') ? 'bg-primary' : 'bg-light text-dark border');
                            ?>
                            <span class="badge <?php echo $badgeColor; ?>"><?php echo $u['plan']; ?></span>
                        </td>
                        <td>
                            <?php if ($u['estado']): ?>
                                <span class="text-success small"><i class="bi bi-circle-fill"></i> Activo</span>
                            <?php else: ?>
                                <span class="text-danger small"><i class="bi bi-circle-fill"></i> Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="usuario_editar.php?id=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if ($u['rol_id'] != 1): // No borrar admins ?>
                            <a href="usuarios.php?borrar=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                                <i class="bi bi-trash"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
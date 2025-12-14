<?php
require_once '../../config/bd.php';

$mensaje = "Validando...";
$tipo_alerta = "info";
$token = $_GET['token'] ?? '';

if ($token) {
    // Buscar token
    $stmt = $conexion->prepare("SELECT usuario_id FROM verificacion_tokens WHERE token = :tok");
    $stmt->execute([':tok' => $token]);
    $row = $stmt->fetch();

    if ($row) {
        // Activar usuario
        $stmtUpd = $conexion->prepare("UPDATE usuarios SET verificado = 1 WHERE id = :uid");
        $stmtUpd->execute([':uid' => $row['usuario_id']]);
        
        // Borrar token
        $conexion->prepare("DELETE FROM verificacion_tokens WHERE usuario_id = ?")->execute([$row['usuario_id']]);
        
        $mensaje = "¡Tu cuenta ha sido verificada exitosamente!";
        $tipo_alerta = "success";
    } else {
        $mensaje = "El enlace de verificación es inválido o ya fue usado.";
        $tipo_alerta = "danger";
    }
} else {
    $mensaje = "No se proporcionó ningún token.";
    $tipo_alerta = "warning";
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificación de Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow p-5 text-center" style="max-width: 500px;">
        <div class="mb-4">
            <?php if($tipo_alerta == 'success'): ?>
                <h1 class="display-1 text-success">✔</h1>
            <?php else: ?>
                <h1 class="display-1 text-secondary">?</h1>
            <?php endif; ?>
        </div>
        <h3 class="mb-3">Estado de Verificación</h3>
        <div class="alert alert-<?php echo $tipo_alerta; ?>">
            <?php echo $mensaje; ?>
        </div>
        <a href="login.php" class="btn btn-primary mt-3">Ir a Iniciar Sesión</a>
    </div>
</body>
</html>
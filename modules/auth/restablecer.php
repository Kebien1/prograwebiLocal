<?php
session_start();
require_once '../../config/bd.php';

$mensaje = "";
$token = $_GET['token'] ?? '';
$tokenValido = false;
$usuario_id = null;

// 1. Validar Token al cargar
if ($token) {
    $stmt = $conexion->prepare("SELECT usuario_id FROM recuperacion_tokens WHERE token = :tok AND expira_el > NOW()");
    $stmt->execute([':tok' => $token]);
    $row = $stmt->fetch();
    
    if ($row) {
        $tokenValido = true;
        $usuario_id = $row['usuario_id'];
    } else {
        $mensaje = "<div class='alert alert-danger'>El enlace es inválido o ha expirado.</div>";
    }
} else {
    $mensaje = "<div class='alert alert-danger'>Token no proporcionado.</div>";
}

// 2. Procesar Nueva Contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenValido) {
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    
    if ($pass1 !== $pass2) {
        $mensaje = "<div class='alert alert-warning'>Las contraseñas no coinciden.</div>";
    } else {
        // Actualizar Usuario
        $hash = password_hash($pass1, PASSWORD_BCRYPT);
        $stmtUpd = $conexion->prepare("UPDATE usuarios SET password = :p WHERE id = :uid");
        $stmtUpd->execute([':p' => $hash, ':uid' => $usuario_id]);
        
        // Borrar Token usado
        $conexion->prepare("DELETE FROM recuperacion_tokens WHERE usuario_id = ?")->execute([$usuario_id]);
        
        $mensaje = "<div class='alert alert-success'>¡Contraseña actualizada! <a href='login.php'>Inicia sesión aquí</a></div>";
        $tokenValido = false; // Ocultar formulario
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-3">
            <h4 class="fw-bold">Restablecer Contraseña</h4>
        </div>
        <?php echo $mensaje; ?>
        
        <?php if ($tokenValido): ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nueva Contraseña</label>
                <input type="password" name="pass1" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar Contraseña</label>
                <input type="password" name="pass2" class="form-control" required minlength="6">
            </div>
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
        </form>
        <?php endif; ?>
        
        <div class="text-center mt-3">
            <a href="login.php" class="text-decoration-none">Ir al Login</a>
        </div>
    </div>
</body>
</html>
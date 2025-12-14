<?php
require_once '../../config/bd.php';

$titulo = "Verificando...";
$mensaje = "Procesando solicitud...";
$icono = "hourglass-split";
$color = "secondary";
$token = $_GET['token'] ?? '';

if ($token) {
    // Buscar token en la BD (coincidiendo con tu diagrama)
    $stmt = $conexion->prepare("SELECT usuario_id FROM verificacion_tokens WHERE token = :tok");
    $stmt->execute([':tok' => $token]);
    $row = $stmt->fetch();

    if ($row) {
        // Activar usuario
        $stmtUpd = $conexion->prepare("UPDATE usuarios SET verificado = 1, estado = 1 WHERE id = :uid");
        $stmtUpd->execute([':uid' => $row['usuario_id']]);
        
        // Eliminar el token usado para limpieza
        $conexion->prepare("DELETE FROM verificacion_tokens WHERE usuario_id = ?")->execute([$row['usuario_id']]);
        
        $titulo = "¡Cuenta Verificada!";
        $mensaje = "Tu correo ha sido confirmado exitosamente. Ya puedes acceder a todos los cursos.";
        $icono = "check-circle-fill";
        $color = "success";
    } else {
        $titulo = "Enlace Inválido";
        $mensaje = "Este enlace de verificación ya fue usado o no existe.";
        $icono = "x-circle-fill";
        $color = "danger";
    }
} else {
    $titulo = "Error";
    $mensaje = "No se proporcionó un token de validación.";
    $icono = "exclamation-triangle-fill";
    $color = "warning";
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificación - EduPlatform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow border-0 p-5 text-center" style="max-width: 450px; width: 90%;">
        <div class="mb-4">
            <i class="bi bi-<?php echo $icono; ?> text-<?php echo $color; ?>" style="font-size: 5rem;"></i>
        </div>
        
        <h2 class="fw-bold mb-3 text-dark"><?php echo $titulo; ?></h2>
        <p class="text-muted mb-4"><?php echo $mensaje; ?></p>
        
        <div class="d-grid">
            <a href="login.php" class="btn btn-primary btn-lg">
                Ir a Iniciar Sesión
            </a>
        </div>
    </div>
</body>
</html>
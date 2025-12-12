<?php
// Archivo: modules/auth/restablecer.php
include("../../config/bd.php");

$email = $_GET['email'] ?? '';
$error = "";
$mensaje = "";

if ($_POST) {
    $emailPost = $_POST['email'];
    $codigo    = $_POST['codigo'];
    $passNueva = $_POST['password'];

    // 1. Verificar si el código y el email coinciden
    $sentencia = $conexion->prepare("SELECT ID FROM usuario WHERE Email = :e AND codigo_otp = :c");
    $sentencia->bindParam(":e", $emailPost);
    $sentencia->bindParam(":c", $codigo);
    $sentencia->execute();
    $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // 2. Actualizar contraseña y borrar el código usado
        $passHash = password_hash($passNueva, PASSWORD_BCRYPT);
        
        $update = $conexion->prepare("UPDATE usuario SET Password = :p, codigo_otp = NULL WHERE ID = :id");
        $update->bindParam(":p", $passHash);
        $update->bindParam(":id", $usuario['ID']);
        $update->execute();

        echo "<script>alert('¡Contraseña actualizada con éxito! Ahora puedes iniciar sesión.'); window.location='login.php';</script>";
        exit;
    } else {
        $error = "El código es incorrecto o ha expirado.";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <title>Nueva Contraseña</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h4 class="text-center mb-4">Restablecer Contraseña</h4>
        
        <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

        <form action="" method="post">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            
            <div class="mb-3">
                <label class="form-label fw-bold">Código de 6 dígitos</label>
                <input type="number" name="codigo" class="form-control text-center fs-4 letter-spacing-2" placeholder="000000" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nueva Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100 fw-bold">Cambiar Contraseña</button>
        </form>
    </div>

</body>
</html>
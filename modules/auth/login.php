<?php
// Archivo: modules/auth/login.php
session_start();
include("../../config/bd.php"); 

$error = "";

if ($_POST) {
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    $sentencia = $conexion->prepare("SELECT * FROM usuario WHERE Email = :e LIMIT 1");
    $sentencia->bindParam(":e", $email);
    $sentencia->execute();
    $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        if (password_verify($password, $usuario['Password'])) {
            if ($usuario['Estado'] == 1) {
                if ($usuario['Verificado'] == 1) {
                    $_SESSION['user_id'] = $usuario['ID'];
                    $_SESSION['nick'] = $usuario['Nick'];
                    $_SESSION['rol'] = $usuario['IdRol'];
                    
                    header("Location: " . $base_url . "dashboard.php");
                    exit;
                } else {
                    $error = "Tu correo no ha sido verificado.";
                }
            } else {
                $error = "Tu cuenta está desactivada.";
            }
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El correo no existe.";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center text-primary mb-4">Iniciar Sesión</h3>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="Email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="Password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Ingresar</button>
        </form>
        
        <div class="text-center">
            <a href="registro.php" class="text-decoration-none">Crear cuenta nueva</a>
            <br>
            <a href="olvido.php" class="text-decoration-none text-muted small">Olvidé mi contraseña</a>
            
            <hr class="my-4">
            
            <a href="../../index.php" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-2"></i>Volver al Inicio
            </a>
        </div>
    </div>

</body>
</html>
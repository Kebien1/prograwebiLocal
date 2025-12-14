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
                // Verificar si pide código (opcional según tu lógica anterior)
                // Aquí asumimos que si está verificado, entra.
                
                $_SESSION['user_id'] = $usuario['ID'];
                $_SESSION['nick'] = $usuario['Nick'];
                $_SESSION['rol'] = $usuario['IdRol'];
                
                // REDIRECCIÓN SEGÚN ROL
                switch($_SESSION['rol']) {
                    case 1: // Admin
                        header("Location: " . $base_url . "modules/admin/dashboard.php");
                        break;
                    case 2: // Estudiante
                        header("Location: " . $base_url . "modules/estudiante/dashboard.php");
                        break;
                    case 3: // Docente
                        header("Location: " . $base_url . "modules/docente/dashboard.php");
                        break;
                    default:
                        header("Location: " . $base_url . "index.php");
                        break;
                }
                exit;

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
    <title>Login - PrograWeb</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow border-0 p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center text-primary mb-4 fw-bold">Iniciar Sesión</h3>
        
        <?php if($error): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" name="Email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Contraseña</label>
                <input type="password" name="Password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3 fw-bold">Ingresar</button>
        </form>
        
        <div class="text-center">
            <a href="registro.php" class="text-decoration-none">Crear cuenta nueva</a>
            <br>
            <a href="olvido.php" class="text-decoration-none text-muted small">Olvidé mi contraseña</a>
            <hr>
            <a href="../../index.php" class="btn btn-outline-secondary w-100 btn-sm">Volver al Inicio</a>
        </div>
    </div>

</body>
</html>
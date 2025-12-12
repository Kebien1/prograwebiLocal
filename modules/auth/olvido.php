<?php
// Archivo: modules/auth/olvido.php
include("../../config/bd.php");
require_once("../../includes/mail_config.php"); // Asegúrate de tener este archivo en includes/

$mensaje = "";
$error = "";

if ($_POST) {
    $email = $_POST['Email'];

    // 1. Buscar si el correo existe
    $sentencia = $conexion->prepare("SELECT ID, Nick FROM usuario WHERE Email = :e LIMIT 1");
    $sentencia->bindParam(":e", $email);
    $sentencia->execute();
    $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // 2. Generar código de 6 dígitos
        $codigo = rand(100000, 999999);

        // 3. Guardar código en la BD
        $update = $conexion->prepare("UPDATE usuario SET codigo_otp = :c WHERE ID = :id");
        $update->bindParam(":c", $codigo);
        $update->bindParam(":id", $usuario['ID']);
        
        if($update->execute()){
            // 4. Enviar correo
            try {
                $mail = crearMailer();
                $mail->addAddress($email, $usuario['Nick']);
                $mail->isHTML(true);
                $mail->Subject = "Recuperar Contraseña";
                $mail->Body = "
                    <h3>Hola " . $usuario['Nick'] . ",</h3>
                    <p>Has solicitado restablecer tu contraseña.</p>
                    <p>Tu código de seguridad es:</p>
                    <h1 style='color:#d9534f; letter-spacing: 5px;'>" . $codigo . "</h1>
                    <p>Úsalo para crear tu nueva clave.</p>
                ";
                $mail->send();
                
                // Redirigir a la pantalla de restablecer pasando el email
                header("Location: restablecer.php?email=" . $email);
                exit;
            } catch (Exception $e) {
                $error = "Error al enviar correo: " . $mail->ErrorInfo;
            }
        }
    } else {
        // Por seguridad, a veces es mejor no decir si el correo existe o no, 
        // pero para facilitar tu uso, mostraremos el error.
        $error = "Ese correo no está registrado.";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <title>Recuperar Contraseña</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h4 class="text-center mb-3">Recuperar Acceso</h4>
        <p class="text-muted text-center small">Ingresa tu correo para recibir un código de seguridad.</p>
        
        <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" name="Email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning w-100 text-dark fw-bold">Enviar Código</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="login.php" class="text-decoration-none text-secondary">Volver</a>
        </div>
    </div>

</body>
</html>
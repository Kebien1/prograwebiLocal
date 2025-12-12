<?php
include("../../config/bd.php");
require_once("../../includes/mail_config.php"); // Tu configuración de mail

$mensaje = "";
$error = "";

if ($_POST) {
    $nick = $_POST['Nick'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    // 1. Verificar si ya existe
    $check = $conexion->prepare("SELECT ID FROM usuario WHERE Email = :e OR Nick = :n");
    $check->bindParam(":e", $email);
    $check->bindParam(":n", $nick);
    $check->execute();

    if ($check->fetch()) {
        $error = "El usuario o correo ya están registrados.";
    } else {
        // 2. Generar código numérico de 6 dígitos
        $codigo = rand(100000, 999999);
        $passHash = password_hash($password, PASSWORD_BCRYPT); // Encriptar clave

        // 3. Guardar usuario (Estado=1 Activo, Verificado=0, Guardamos codigo_otp)
        // NOTA: Asegúrate de haber agregado la columna 'codigo_otp' en tu base de datos como te indiqué antes.
        $sql = "INSERT INTO usuario (Nick, Email, Password, Estado, Verificado, IdRol, codigo_otp) 
                VALUES (:n, :e, :p, 1, 0, 2, :c)";
        
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(":n", $nick);
        $sentencia->bindParam(":e", $email);
        $sentencia->bindParam(":p", $passHash);
        $sentencia->bindParam(":c", $codigo);
        
        if ($sentencia->execute()) {
            // 4. Enviar correo con el número
            $mail = crearMailer(); // Función de tu includes/mail_config.php
            try {
                $mail->addAddress($email, $nick);
                $mail->isHTML(true);
                $mail->Subject = "Codigo de Verificacion";
                $mail->Body = "
                    <h3>Hola $nick,</h3>
                    <p>Tu código de verificación es:</p>
                    <h1 style='color:blue; letter-spacing: 5px;'>$codigo</h1>
                    <p>Ingrésalo en la página para activar tu cuenta.</p>
                ";
                $mail->send();
                
                // Redirigir a la pantalla de poner el código
                header("Location: verificar.php?email=" . $email);
                exit;
            } catch (Exception $e) {
                $error = "Usuario registrado pero falló el envío del correo: " . $mail->ErrorInfo;
            }
        } else {
            $error = "Error al registrar en base de datos.";
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <title>Registro</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow p-4" style="width: 100%; max-width: 450px;">
        <h3 class="text-center text-success mb-4">Crear Cuenta</h3>
        
        <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Nombre de Usuario</label>
                <input type="text" name="Nick" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="Email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="Password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100 mb-3">Registrarse</button>
        </form>
        
        <div class="text-center">
            <a href="login.php" class="text-decoration-none">Volver al Login</a>
        </div>
    </div>

</body>
</html>
<?php
session_start();
require_once '../../config/bd.php';
require_once '../../config/mail_config.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    // 1. Buscar si el usuario existe
    $stmt = $conexion->prepare("SELECT id, nombre_completo FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // 2. Generar Token único
        $token = bin2hex(random_bytes(32));
        // Expira en 1 hora
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour')); 

        // 3. Guardar en BD (Borramos tokens viejos primero)
        $conexion->prepare("DELETE FROM recuperacion_tokens WHERE usuario_id = ?")->execute([$user['id']]);
        
        $sql = "INSERT INTO recuperacion_tokens (usuario_id, token, expira_el) VALUES (:uid, :tok, :exp)";
        $stmtInsert = $conexion->prepare($sql);
        $stmtInsert->execute([':uid' => $user['id'], ':tok' => $token, ':exp' => $expira]);

        // 4. Enviar Correo
        $mail = crearMailer();
        if ($mail) {
            try {
                $mail->addAddress($email, $user['nombre_completo']);
                $mail->isHTML(true);
                $mail->Subject = 'Recuperar Contraseña - EduPlatform';
                
                $link = BASE_URL . "modules/auth/restablecer.php?token=" . $token;
                
                $mail->Body = "
                    <h3>Hola, {$user['nombre_completo']}</h3>
                    <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace:</p>
                    <p><a href='$link' style='background:#0d6efd;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Restablecer Contraseña</a></p>
                    <p>Si no fuiste tú, ignora este mensaje.</p>
                ";
                
                $mail->send();
                $mensaje = "<div class='alert alert-success'>Se ha enviado un enlace a tu correo.</div>";
            } catch (Exception $e) {
                $mensaje = "<div class='alert alert-danger'>Error al enviar correo: {$mail->ErrorInfo}</div>";
            }
        } else {
            $mensaje = "<div class='alert alert-danger'>Error de configuración de correo.</div>";
        }
    } else {
        // Por seguridad, mostramos el mismo mensaje aunque no exista
        $mensaje = "<div class='alert alert-info'>Si el correo existe, recibirás instrucciones.</div>";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-3">
            <h4 class="fw-bold">Recuperar Acceso</h4>
            <p class="text-muted small">Ingresa tu correo para buscar tu cuenta</p>
        </div>
        <?php echo $mensaje; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar Enlace</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php" class="text-decoration-none">Volver al Login</a>
        </div>
    </div>
</body>
</html>
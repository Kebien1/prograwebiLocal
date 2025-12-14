<?php
session_start();
require_once '../../config/bd.php';
require_once '../../config/mail_config.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    // 1. Verificar usuario
    $stmt = $conexion->prepare("SELECT id, nombre_completo FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // 2. Generar Token y Expiración (1 hora)
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // 3. Guardar en BD (limpiando previos)
        $conexion->prepare("DELETE FROM recuperacion_tokens WHERE usuario_id = ?")->execute([$user['id']]);
        
        $sql = "INSERT INTO recuperacion_tokens (usuario_id, token, expira_el) VALUES (:uid, :tok, :exp)";
        $stmtInsert = $conexion->prepare($sql);
        $stmtInsert->execute([':uid' => $user['id'], ':tok' => $token, ':exp' => $expira]);

        // 4. Enviar Correo con Diseño Amigable
        $mail = crearMailer();
        if ($mail) {
            try {
                $mail->addAddress($email, $user['nombre_completo']);
                $mail->isHTML(true);
                $mail->Subject = 'Recuperar Acceso - EduPlatform';
                
                $link = BASE_URL . "modules/auth/restablecer.php?token=" . $token;
                
                // Diseño HTML profesional
                $mail->Body = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                        <h2 style='color: #0d6efd; text-align: center;'>Restablecer Contraseña</h2>
                        <p>Hola <strong>{$user['nombre_completo']}</strong>,</p>
                        <p>Hemos recibido una solicitud para cambiar tu contraseña. Si fuiste tú, haz clic en el botón de abajo:</p>
                        <br>
                        <p style='text-align: center;'>
                            <a href='$link' style='background-color: #0d6efd; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;'>Cambiar Contraseña</a>
                        </p>
                        <br>
                        <p style='color: #666; font-size: 14px;'>Este enlace expirará en 1 hora por seguridad.</p>
                        <hr style='border: 0; border-top: 1px solid #eee;'>
                        <p style='color: #999; font-size: 12px; text-align: center;'>Si no solicitaste este cambio, ignora este correo.</p>
                    </div>
                ";
                
                $mail->send();
                $mensaje = "<div class='alert alert-success'><i class='bi bi-envelope-check'></i> Te enviamos un correo con las instrucciones.</div>";
            } catch (Exception $e) {
                $mensaje = "<div class='alert alert-danger'>Error al enviar: {$mail->ErrorInfo}</div>";
            }
        }
    } else {
        // Mensaje genérico por seguridad (para no revelar qué correos existen)
        $mensaje = "<div class='alert alert-info'><i class='bi bi-info-circle'></i> Si el correo existe, recibirás instrucciones.</div>";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow border-0 p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <div class="mb-3">
                <i class="bi bi-shield-lock text-primary display-1"></i>
            </div>
            <h4 class="fw-bold">¿Olvidaste tu contraseña?</h4>
            <p class="text-muted small">No te preocupes, te ayudamos a recuperarla.</p>
        </div>
        
        <?php echo $mensaje; ?>
        
        <form method="post">
            <div class="mb-3">
                <label class="form-label fw-bold">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Enviar Enlace</button>
        </form>
        
        <div class="text-center mt-4">
            <a href="login.php" class="text-decoration-none text-secondary">
                <i class="bi bi-arrow-left"></i> Volver al Login
            </a>
        </div>
    </div>
</body>
</html>
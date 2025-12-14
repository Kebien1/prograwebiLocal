<?php
session_start();
require_once '../../config/bd.php';
require_once '../../config/mail_config.php'; // Incluimos la config de correo

$mensaje = "";
$plan_preseleccionado = $_GET['plan'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $plan_id = $_POST['plan_id'];

    if (empty($nombre) || empty($email) || empty($password)) {
        $mensaje = "<div class='alert alert-danger'>Por favor completa todos los campos.</div>";
    } else {
        // 1. Validar si el correo ya existe
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {
            $mensaje = "<div class='alert alert-warning'>El correo ya está registrado.</div>";
        } else {
            // 2. Crear usuario (verificado = 0, estado = 1)
            $passHash = password_hash($password, PASSWORD_BCRYPT);
            
            try {
                $conexion->beginTransaction();

                // A. Insertar en tabla 'usuarios' (según tu diagrama)
                $sqlUser = "INSERT INTO usuarios (nombre_completo, email, password, rol_id, plan_id, estado, verificado, fecha_registro) 
                            VALUES (:nom, :email, :pass, 3, :plan, 1, 0, NOW())";
                $stmtInsert = $conexion->prepare($sqlUser);
                $stmtInsert->execute([
                    ':nom' => $nombre,
                    ':email' => $email,
                    ':pass' => $passHash,
                    ':plan' => $plan_id
                ]);
                $usuario_id = $conexion->lastInsertId();

                // B. Generar Token y guardar en 'verificacion_tokens'
                $token = bin2hex(random_bytes(32));
                // Tu diagrama tiene columna 'creado_el', usamos NOW()
                $sqlToken = "INSERT INTO verificacion_tokens (usuario_id, token, creado_el) VALUES (?, ?, NOW())";
                $conexion->prepare($sqlToken)->execute([$usuario_id, $token]);

                // C. Enviar Correo
                $mail = crearMailer();
                if ($mail) {
                    $mail->addAddress($email, $nombre);
                    $mail->isHTML(true);
                    $mail->Subject = 'Activa tu cuenta - EduPlatform';
                    
                    $link = BASE_URL . "modules/auth/verificar.php?token=" . $token;
                    
                    $mail->Body = "
                        <h3>¡Hola $nombre!</h3>
                        <p>Gracias por registrarte. Para iniciar sesión, confirma tu cuenta aquí:</p>
                        <p><a href='$link' style='padding:10px; background:#0d6efd; color:white; text-decoration:none; border-radius:5px;'>Verificar Email</a></p>
                    ";
                    
                    $mail->send();
                    $conexion->commit();
                    
                    // Redirigir al login avisando
                    header("Location: login.php?mensaje=registrado");
                    exit;
                } else {
                    throw new Exception("Error al configurar el envío de correo.");
                }

            } catch (Exception $e) {
                $conexion->rollBack();
                $mensaje = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - EduPlatform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow border-0 p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center text-primary fw-bold mb-3">Crear Cuenta</h3>
        <?php echo $mensaje; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Plan</label>
                <select name="plan_id" class="form-select">
                    <option value="1" <?php if($plan_preseleccionado==1) echo 'selected'; ?>>Básico (1 Dispositivo)</option>
                    <option value="2" <?php if($plan_preseleccionado==2) echo 'selected'; ?>>Pro (3 Dispositivos)</option>
                    <option value="3" <?php if($plan_preseleccionado==3) echo 'selected'; ?>>Premium (5 Dispositivos)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registrarme</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php">¿Ya tienes cuenta? Entra aquí</a>
        </div>
    </div>
</body>
</html>
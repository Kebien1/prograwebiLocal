<?php
session_start();
require_once '../../config/bd.php';

// Si llega mensaje de registro
$mensaje = "";
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'registrado') {
    $mensaje = "<div class='alert alert-success'>¡Registro exitoso! Por favor verifica tu correo antes de entrar.</div>";
}
if (isset($_GET['error']) && $_GET['error'] == 'expulsado') {
    $mensaje = "<div class='alert alert-warning'><strong>Sesión cerrada.</strong> Se ha abierto tu cuenta en otro dispositivo y superaste el límite de tu plan.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Obtener datos del usuario + límite de sesiones del plan
    $sql = "SELECT u.*, p.limite_sesiones, p.nombre as plan_nombre 
            FROM usuarios u 
            JOIN planes p ON u.plan_id = p.id 
            WHERE u.email = :email AND u.estado = 1 LIMIT 1";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password'])) {
        
        // 2. VERIFICAR EMAIL (Columna 'verificado' de tu diagrama)
        if ($usuario['verificado'] == 0) {
            $mensaje = "<div class='alert alert-warning'>Tu cuenta no está verificada. Revisa tu email.</div>";
        } else {
            // --- CONTROL DE SESIONES ---
            
            // A. Contar cuántas sesiones tiene abiertas
            $stmtCount = $conexion->prepare("SELECT COUNT(*) FROM sesiones_activas WHERE usuario_id = ?");
            $stmtCount->execute([$usuario['id']]);
            $sesiones_actuales = $stmtCount->fetchColumn();

            // B. Si alcanza o supera el límite, borrar la más antigua
            if ($sesiones_actuales >= $usuario['limite_sesiones']) {
                $borrar = ($sesiones_actuales - $usuario['limite_sesiones']) + 1;
                // Borramos la que tenga 'ultimo_acceso' más viejo
                $sqlDel = "DELETE FROM sesiones_activas WHERE usuario_id = :uid ORDER BY ultimo_acceso ASC LIMIT $borrar";
                $conexion->prepare($sqlDel)->execute([':uid' => $usuario['id']]);
            }

            // C. Crear nueva sesión
            session_regenerate_id(true);
            $session_id = session_id();
            $ip = $_SERVER['REMOTE_ADDR'];
            $ua = $_SERVER['HTTP_USER_AGENT']; // Tu diagrama tiene esta columna

            $sqlSesion = "INSERT INTO sesiones_activas (session_id, usuario_id, ip_address, user_agent, ultimo_acceso) 
                          VALUES (:sid, :uid, :ip, :ua, NOW())";
            $stmtSesion = $conexion->prepare($sqlSesion);
            $stmtSesion->execute([
                ':sid' => $session_id,
                ':uid' => $usuario['id'],
                ':ip' => $ip,
                ':ua' => $ua
            ]);

            // D. Guardar variables de sesión PHP
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre_completo'];
            $_SESSION['rol_id'] = $usuario['rol_id'];
            $_SESSION['plan_nombre'] = $usuario['plan_nombre'];

            // Redirección por rol
            if ($usuario['rol_id'] == 1) header("Location: ../admin/dashboard.php");
            elseif ($usuario['rol_id'] == 2) header("Location: ../docente/dashboard.php");
            else header("Location: ../estudiante/dashboard.php");
            exit;
        }

    } else {
        $mensaje = "<div class='alert alert-danger'>Correo o contraseña incorrectos.</div>";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - EduPlatform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-primary d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow border-0 p-4" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">Bienvenido</h3>
            <p class="text-muted">Ingresa a tu cuenta</p>
        </div>
        <?php echo $mensaje; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label fw-bold">Correo</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Entrar</button>
        </form>
        <div class="text-center">
            <a href="recuperar.php" class="small">Olvidé mi contraseña</a> | 
            <a href="registro.php" class="small">Crear Cuenta</a>
            <br><br>
            <a href="../../index.php" class="text-secondary small">← Volver al inicio</a>
        </div>
    </div>
</body>
</html>
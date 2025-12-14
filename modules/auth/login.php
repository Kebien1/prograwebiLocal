<?php
session_start();
require_once '../../config/bd.php';

// Si ya está logueado, redirigir al índice para que lo mande a su dashboard
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../../index.php"); 
    exit;
}

$mensaje = "";
if (isset($_GET['registrado'])) {
    $mensaje = "<div class='alert alert-success'>¡Registro exitoso! Por favor inicia sesión.</div>";
}
if (isset($_GET['error']) && $_GET['error'] == 'expulsado') {
    $mensaje = "<div class='alert alert-warning'><strong>Sesión cerrada.</strong> Se ha iniciado sesión en otro dispositivo y has superado el límite de tu plan.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Buscar usuario y saber cuál es su límite de sesiones según su plan
    $sql = "SELECT u.id, u.nombre_completo, u.password, u.rol_id, u.plan_id, p.limite_sesiones, p.nombre as plan_nombre 
            FROM usuarios u 
            JOIN planes p ON u.plan_id = p.id 
            WHERE u.email = :email AND u.estado = 1 LIMIT 1";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password'])) {
        
        // ==========================================================
        // LÓGICA DE CONTROL DE SESIONES (ANTI-PRÉSTAMO DE CUENTAS)
        // ==========================================================
        
        // A. Contar sesiones activas actuales
        $stmtCount = $conexion->prepare("SELECT COUNT(*) FROM sesiones_activas WHERE usuario_id = :uid");
        $stmtCount->execute([':uid' => $usuario['id']]);
        $sesiones_actuales = $stmtCount->fetchColumn();

        // B. Verificar límite
        // Si tiene 1 sesión activa y su límite es 1, tenemos que borrar esa sesión vieja para que entre esta nueva.
        if ($sesiones_actuales >= $usuario['limite_sesiones']) {
            // Calcular cuántas borrar. Normalmente borramos 1 (la más vieja)
            $cantidad_a_borrar = ($sesiones_actuales - $usuario['limite_sesiones']) + 1;
            
            // Borrar las sesiones más antiguas (ORDER BY ultimo_acceso ASC)
            $sqlBorrar = "DELETE FROM sesiones_activas WHERE usuario_id = :uid ORDER BY ultimo_acceso ASC LIMIT $cantidad_a_borrar";
            $stmtBorrar = $conexion->prepare($sqlBorrar);
            $stmtBorrar->execute([':uid' => $usuario['id']]);
        }

        // C. Registrar la nueva sesión
        session_regenerate_id(true); // Seguridad: regenerar ID de sesión
        $session_id_php = session_id();
        $ip = $_SERVER['REMOTE_ADDR'];

        $stmtInsert = $conexion->prepare("INSERT INTO sesiones_activas (session_id, usuario_id, ip_address) VALUES (:sid, :uid, :ip)");
        $stmtInsert->execute([
            ':sid' => $session_id_php,
            ':uid' => $usuario['id'],
            ':ip' => $ip
        ]);

        // ==========================================================
        
        // Guardar datos en la sesión del navegador
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre_completo'];
        $_SESSION['rol_id'] = $usuario['rol_id'];
        $_SESSION['plan_nombre'] = $usuario['plan_nombre'];

        // Redirigir según el Rol
        // 1=Admin, 2=Docente, 3=Estudiante
        if ($usuario['rol_id'] == 1) {
            header("Location: ../admin/dashboard.php");
        } elseif ($usuario['rol_id'] == 2) {
            header("Location: ../docente/dashboard.php");
        } else {
            header("Location: ../estudiante/dashboard.php");
        }
        exit;

    } else {
        $mensaje = "<div class='alert alert-danger'>Credenciales incorrectas.</div>";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - EduPlatform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-primary d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow border-0 p-4 m-3" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">Bienvenido</h3>
            <p class="text-muted">Ingresa a tu cuenta</p>
        </div>

        <?php echo $mensaje; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" name="email" class="form-control form-control-lg" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Contraseña</label>
                <input type="password" name="password" class="form-control form-control-lg" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">Entrar</button>
        </form>

        <div class="text-center">
            <a href="recuperar.php" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
            <hr>
            <p class="mb-1">¿Nuevo aquí?</p>
            <a href="registro.php" class="btn btn-outline-dark w-100">Crear Cuenta Gratis</a>
            <br><br>
            <a href="../../index.php" class="text-secondary text-decoration-none small">← Volver al inicio</a>
        </div>
    </div>

</body>
</html>
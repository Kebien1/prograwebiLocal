<?php
session_start();
// Ajusta la ruta para llegar a config/bd.php (subimos 2 niveles)
require_once '../../config/bd.php';

$mensaje = "";
// Si el usuario viene desde la página de inicio, capturamos el plan que eligió
$plan_preseleccionado = $_GET['plan'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $plan_id = $_POST['plan_id'];

    if (empty($nombre) || empty($email) || empty($password)) {
        $mensaje = "<div class='alert alert-danger'>Por favor completa todos los campos.</div>";
    } else {
        // 1. Verificar si el correo ya existe
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {
            $mensaje = "<div class='alert alert-warning'>El correo ya está registrado. <a href='login.php'>Inicia sesión aquí</a></div>";
        } else {
            // 2. Crear el usuario
            // Rol 3 = Estudiante. Estado 1 = Activo.
            $passHash = password_hash($password, PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO usuarios (nombre_completo, email, password, rol_id, plan_id, estado, verificado) 
                    VALUES (:nombre, :email, :pass, 3, :plan, 1, 0)";
            
            try {
                $stmtInsert = $conexion->prepare($sql);
                $stmtInsert->execute([
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':pass' => $passHash,
                    ':plan' => $plan_id
                ]);
                
                // Redirigir al login avisando que se registró
                header("Location: login.php?registrado=1");
                exit;
            } catch (PDOException $e) {
                $mensaje = "<div class='alert alert-danger'>Error técnico: " . $e->getMessage() . "</div>";
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
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="card shadow border-0 p-4 m-3" style="max-width: 450px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">Crear una Cuenta</h3>
            <p class="text-muted">Empieza a aprender hoy mismo</p>
        </div>

        <?php echo $mensaje; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label fw-bold">Nombre Completo</label>
                <input type="text" name="nombre" class="form-control form-control-lg" required placeholder="Tu nombre">
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Correo Electrónico</label>
                <input type="email" name="email" class="form-control form-control-lg" required placeholder="ejemplo@correo.com">
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Contraseña</label>
                <input type="password" name="password" class="form-control form-control-lg" required placeholder="********">
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold text-primary">Plan Seleccionado</label>
                <select name="plan_id" class="form-select form-select-lg">
                    <option value="1" <?php echo ($plan_preseleccionado == 1) ? 'selected' : ''; ?>>Plan Básico (1 Dispositivo) - Gratis</option>
                    <option value="2" <?php echo ($plan_preseleccionado == 2) ? 'selected' : ''; ?>>Plan Pro (3 Dispositivos) - $15</option>
                    <option value="3" <?php echo ($plan_preseleccionado == 3) ? 'selected' : ''; ?>>Plan Premium (5 Dispositivos) - $29</option>
                </select>
                <div class="form-text">Puedes cambiar tu plan más adelante.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg mb-3">Registrarme</button>
        </form>

        <div class="text-center">
            <p class="mb-1">¿Ya tienes cuenta? <a href="login.php" class="text-decoration-none fw-bold">Inicia Sesión</a></p>
            <a href="../../index.php" class="text-secondary text-decoration-none small">← Volver al inicio</a>
        </div>
    </div>

</body>
</html>
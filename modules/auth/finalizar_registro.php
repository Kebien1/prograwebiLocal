<?php
session_start();
require_once '../../config/bd.php';
require_once '../../config/mail_config.php';

// Verificamos si hay datos de registro en la sesión
if (!isset($_SESSION['temp_registro'])) {
    header("Location: registro.php");
    exit;
}

$datos = $_SESSION['temp_registro'];
$nombre = $datos['nombre'];
$email = $datos['email'];
$plan_id = $datos['plan_id'];
// Encriptamos la contraseña aquí
$passHash = password_hash($datos['password'], PASSWORD_BCRYPT);

try {
    $conexion->beginTransaction();

    // 1. Insertar Usuario
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

    // 2. Generar Token de Verificación
    $token = bin2hex(random_bytes(32));
    $sqlToken = "INSERT INTO verificacion_tokens (usuario_id, token, creado_el) VALUES (?, ?, NOW())";
    $conexion->prepare($sqlToken)->execute([$usuario_id, $token]);

    // 3. Enviar Correo
    $mail = crearMailer();
    if ($mail) {
        $mail->addAddress($email, $nombre);
        $mail->isHTML(true);
        $mail->Subject = 'Bienvenido a EduPlatform - Confirma tu cuenta';
        
        $link = BASE_URL . "modules/auth/verificar.php?token=" . $token;
        
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; max-width: 500px;'>
                <h2 style='color: #0d6efd;'>¡Pago Exitoso!</h2>
                <p>Hola <strong>$nombre</strong>,</p>
                <p>Tu suscripción ha sido procesada correctamente. Ya eres parte de EduPlatform.</p>
                <p>Por favor, verifica tu correo para empezar a aprender:</p>
                <br>
                <div style='text-align: center;'>
                    <a href='$link' style='background: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                        Activar mi Cuenta
                    </a>
                </div>
            </div>
        ";
        $mail->send();
    }

    $conexion->commit();
    
    // Limpiamos la sesión temporal
    unset($_SESSION['temp_registro']);
    
    // Redirigimos al login con éxito
    header("Location: login.php?mensaje=registrado");
    exit;

} catch (Exception $e) {
    $conexion->rollBack();
    die("Error crítico al registrar: " . $e->getMessage());
}
?>
<?php
include("../../config/bd.php");

$email = $_GET['email'] ?? '';
$mensaje = "";
$error = "";

if ($_POST) {
    $emailPost = $_POST['email'];
    $codigoIngresado = $_POST['codigo'];

    // Buscar usuario con ese email y ese código
    $sql = $conexion->prepare("SELECT ID FROM usuario WHERE Email = :e AND codigo_otp = :c");
    $sql->bindParam(":e", $emailPost);
    $sql->bindParam(":c", $codigoIngresado);
    $sql->execute();
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Código correcto: Verificar usuario y borrar código
        $update = $conexion->prepare("UPDATE usuario SET Verificado = 1, codigo_otp = NULL WHERE ID = :id");
        $update->bindParam(":id", $usuario['ID']);
        $update->execute();
        
        // Redirigir al login con mensaje
        echo "<script>alert('¡Cuenta verificada correctamente!'); window.location='login.php';</script>";
        exit;
    } else {
        $error = "El código ingresado es incorrecto.";
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <title>Verificar Cuenta</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow p-4 text-center" style="width: 100%; max-width: 400px;">
        <h3 class="mb-3">Verificación</h3>
        <p>Hemos enviado un código a: <strong><?php echo htmlspecialchars($email); ?></strong></p>
        
        <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

        <form action="" method="post">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <div class="mb-3">
                <input type="number" name="codigo" class="form-control text-center fs-3 letter-spacing-2" placeholder="000000" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Verificar</button>
        </form>
    </div>

</body>
</html>
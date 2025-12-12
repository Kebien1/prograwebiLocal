<?php
$servidor = "localhost";
$basededatos = "if0_40651214_db_prograwebi";
$usuario = "root";
$clave = "";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$basededatos;charset=utf8mb4", $usuario, $clave);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $ex) {
    echo "Error de conexión: " . $ex->getMessage();
    exit;
}
?>


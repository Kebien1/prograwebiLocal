<?php
// Tienes que asegurarte que esta palabra coincida con tu carpeta en htdocs
// Si tu carpeta se llama "prograwebiLocal", pon eso aquí:
define('BASE_URL', 'http://localhost/PROYECTOLOCALHOST/'); 

// ... resto del código de conexión ...
$servidor = "localhost";
$basededatos = "db_prograwebi"; 
$usuario = "root";
$clave = "";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$basededatos;charset=utf8mb4", $usuario, $clave);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    date_default_timezone_set('America/La_Paz');
} catch(PDOException $ex) {
    die("Error de conexión: " . $ex->getMessage());
}
?>
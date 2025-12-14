<?php
// Definir la ruta base del proyecto (ajusta si cambias el nombre de la carpeta)
define('BASE_URL', 'http://localhost/prograwebLocal/');

$servidor = "localhost";
$basededatos = "db_prograwebi"; 
$usuario = "root";
$clave = "";

try {
    // Crear conexión PDO
    $conexion = new PDO("mysql:host=$servidor;dbname=$basededatos;charset=utf8mb4", $usuario, $clave);
    
    // Configurar errores y zona horaria
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    date_default_timezone_set('America/La_Paz'); // Ajusta a tu zona horaria

} catch(PDOException $ex) {
    // Mensaje simple si falla
    die("Error de conexión: " . $ex->getMessage());
}
?>
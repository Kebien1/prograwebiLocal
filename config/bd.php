<?php
// Archivo: config/bd.php

// 1. Datos de conexión
$servidor = "localhost";
$basededatos = "if0_40651214_db_prograwebi"; 
$usuario = "root";
$clave = "";

// 2. Ruta base del proyecto (IMPORTANTE: Cámbialo si tu carpeta tiene otro nombre)
// Esto nos ayuda a crear enlaces que funcionen desde cualquier carpeta
$base_url = "http://localhost/prograwebiLocal/"; 

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$basededatos;charset=utf8mb4", $usuario, $clave);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $ex) {
    echo "Error de conexión: " . $ex->getMessage();
    exit;
}
?>
<?php
session_start();
require_once '../../config/bd.php';

// Validar sesión
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 3) {
    header("Location: ../../index.php");
    exit;
}

// Obtener datos
$tipo = $_GET['tipo'] ?? '';
$id_item = $_GET['id'] ?? 0;
$precio = $_GET['precio'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

if ($tipo && $id_item) {
    try {
        // Registrar compra
        $sql = "INSERT INTO compras (usuario_id, item_id, tipo_item, monto_pagado) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$usuario_id, $id_item, $tipo, $precio]);
        
        // Redirigir a "Mis Compras" con éxito
        header("Location: mis_compras.php?exito=1");
    } catch (Exception $e) {
        die("Error al procesar compra: " . $e->getMessage());
    }
} else {
    header("Location: catalogo.php?error=datos_invalidos");
}
?>
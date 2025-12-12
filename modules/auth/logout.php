<?php
// Archivo: modules/auth/logout.php
session_start();
session_destroy();
// Redirigir al login
header("Location: login.php");
exit;
?>
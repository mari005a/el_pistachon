<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'comprador') {
    header("Location: login.php");
    exit();
}
?>
<h1>Bienvenido Comprador</h1>
<a href="logout.php">Cerrar sesión</a>
<?php
session_start();

// Verificar que el usuario esté logueado y sea usuario
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'usuario') {
    header("Location: login.php?msg=error_user");
    exit;
}
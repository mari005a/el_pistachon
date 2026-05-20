<?php
$servername = "sql204.infinityfree.com";   // o la IP de tu servidor
$username   = "if0_40269598";        // tu usuario de MariaDB
$password   = "Xk0rpi025";            // tu contraseña de MariaDB
$dbname     = "if0_40269598_tienda_db";   // la base de datos que creamos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
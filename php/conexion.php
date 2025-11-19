<?php
$servername = "140.10.1.234";   // o la IP de tu servidor
$username   = "root";        // tu usuario de MariaDB
$password   = "mariana";            // tu contraseña de MariaDB
$dbname     = "tienda_db";   // la base de datos que creamos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
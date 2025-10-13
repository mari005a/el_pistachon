<?php
$servername = "127.0.0.1:3307";   // o la IP de tu servidor
$username   = "root";        // tu usuario de MariaDB
$password   = "Luis12345";            // tu contraseña de MariaDB
$dbname     = "tienda_db";   // la base de datos que creamos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
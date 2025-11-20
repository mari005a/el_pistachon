<?php
include 'conexion.php';

// Datos del admin que quieres crear
$nombre = "Administrador Principal";
$email = "admin@correo.com";
$passwordPlano = "123456"; // puedes cambiarlo por la contraseña que quieras
$rol = "admin";

// Encriptar la contraseña
$hash = password_hash($passwordPlano, PASSWORD_DEFAULT);

// Verificar si ya existe un admin con ese correo
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo "Ya existe un usuario con el correo $email";
} else {
    // Insertar el nuevo admin
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $hash, $rol);

    if ($stmt->execute()) {
        echo "Admin creado correctamente.<br>";
        echo "Correo: $email<br>";
        echo "Contraseña: $passwordPlano<br>";
    } else {
        echo "Error al crear admin: " . $conn->error;
    }
}
?>
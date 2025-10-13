<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = trim($_POST['nombre']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Rol fijo para todos los registros
    $rol = "usuario";

    // Validaciones
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?msg=invalid_email");
        exit;
    }
    if (strlen($password) < 6) {
        header("Location: register.php?msg=short_pass");
        exit;
    }

    // Encriptar contraseña
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $hash, $rol);

    if ($stmt->execute()) {
        header("Location: register.php?msg=success");
        exit;
    } else {
        if ($conn->errno == 1062) {
            header("Location: register.php?msg=duplicate");
        } else {
            header("Location: register.php?msg=error");
        }
        exit;
    }
}
?>
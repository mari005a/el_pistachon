<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Buscar usuario en BD usando email
    $sql = "SELECT id, nombre, rol, password FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verificar contraseña
        if (password_verify($password, $row['password'])) {
            // Guardar variables de sesión
            $_SESSION['id'] = $row['id'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['rol'] = $row['rol'];

            // Redirigir según rol
            if ($row['rol'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: catalogo.php");
            }
            exit;
        } else {
            header("Location: login.php?msg=error_pass");
            exit;
        }
    } else {
        header("Location: login.php?msg=error_user");
        exit;
    }
}
?>
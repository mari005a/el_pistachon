<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            if ($_SESSION['rol'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: ../html/catalogo.html");
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
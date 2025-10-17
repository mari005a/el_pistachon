<?php
session_start();

// Verificar que el usuario esté logueado y sea admin
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php?msg=error_user");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <header class="header">
    <div class="menu container">
      <a href="#" class="logo">Mi Web</a>
      <input type="checkbox" id="menu" />
      <label for="menu">
        <img src="../imagenes/menu.png" class="menu-icono" alt="menu">
      </label>
      <nav class="navbar">
        <ul>
          <li><a href="../html/index.html">Inicio</a></li>
          <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
      </nav>
    </div>

    <div class="header-content container">
      <h1>Bienvenido Administrador</h1>
      <p>Hola, <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>.  
      Has iniciado sesión correctamente como <b>administrador</b>.</p>
      <a href="logout.php" class="btn-1">Cerrar sesión</a>
    </div>
  </header>

</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
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
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Registro</a></li>
        </ul>
      </nav>
    </div>

    <div class="header-content container">
      <h1>Crear cuenta</h1>

      <!-- Mensajes de error -->
      <?php if (isset($_GET['msg'])): ?>
      <div class="msg" style="margin-bottom:10px; text-align:center;">
          <?php
          if ($_GET['msg'] == 'success') echo "<span style='color:green;'>Usuario registrado correctamente</span>";
          if ($_GET['msg'] == 'duplicate') echo "<span style='color:red;'>El correo ya está registrado</span>";
          if ($_GET['msg'] == 'invalid_email') echo "<span style='color:red;'>El correo no es válido</span>";
          if ($_GET['msg'] == 'short_pass') echo "<span style='color:red;'>La contraseña debe tener al menos 6 caracteres</span>";
          if ($_GET['msg'] == 'invalid_role') echo "<span style='color:red;'>Rol inválido</span>";
          if ($_GET['msg'] == 'error') echo "<span style='color:red;'>Error al registrar usuario</span>";
          ?>
      </div>
      <?php endif; ?>

      <form action="register_action.php" method="POST" style="max-width:400px; margin:0 auto; text-align:left;">
        <input type="text" name="nombre" placeholder="Nombre completo" required style="width:100%; padding:10px; margin:10px 0;">
        <input type="email" name="email" placeholder="Correo electrónico" required style="width:100%; padding:10px; margin:10px 0;">
        <input type="password" name="password" placeholder="Contraseña" required style="width:100%; padding:10px; margin:10px 0;">
        <button type="submit" class="btn-1" style="width:100%; margin-top:15px;">Registrar</button>
      </form>
    </div>
  </header>

</body>
</html>
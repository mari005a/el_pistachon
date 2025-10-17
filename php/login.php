<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <header class="header">
    <div class="menu container">
      <a href="#" class="logo">Mi Web</a>
      <input type="checkbox" id="menu" />
      <label for="menu">
        <img src="imagenes/menu.png" class="menu-icono" alt="menu">
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
      <h1>Iniciar sesión</h1>

      <!-- Mensajes de error -->
      <?php if (isset($_GET['msg'])): ?>
        <div class="msg" style="margin-bottom:10px; text-align:center;">
          <?php
            if ($_GET['msg'] == 'error_pass') echo "<span style='color:red;'>Contraseña incorrecta</span>";
            if ($_GET['msg'] == 'error_user') echo "<span style='color:red;'>Usuario no encontrado</span>";
            if ($_GET['msg'] == 'logout') echo "<span style='color:green;'>Sesión cerrada correctamente</span>";
          ?>
        </div>
      <?php endif; ?>

      <form action="login_action.php" method="POST" style="max-width:400px; margin:0 auto; text-align:left;">
        <input type="email" name="email" placeholder="Correo electrónico" required style="width:100%; padding:10px; margin:10px 0;">
        <input type="password" name="password" placeholder="Contraseña" required style="width:100%; padding:10px; margin:10px 0;">
        <button type="submit" class="btn-1" style="width:100%; margin-top:15px;">Entrar</button>
      </form>
    </div>
  </header>

</body>
</html>
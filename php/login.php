<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión | El Pistachón</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body style="margin:0; font-family:'Poppins', sans-serif; background-color:#fafafa; display:flex; flex-direction:column; min-height:100vh;">

  <header style="background-color:#f5f5f5; padding:10px 0; border-bottom:1px solid #ccc;">
    <div style="display:flex; justify-content:space-between; align-items:center; max-width:1200px; margin:0 auto; padding:0 40px;">
      
    <a href="index.html">
                <img src="../imagenes/logo_pistachon.png" alt="El Pistachón" class="logo-img">
            </a>

      <nav>
        <ul style="list-style:none; display:flex; gap:40px; margin:0; padding:0;">
          <li><a href="../html/index.html" style="text-decoration:none; color:#333; font-weight:500;">Inicio</a></li>
          <li><a href="php/catalogo.php" style="text-decoration:none; color:#333; font-weight:500;">Catálogo</a></li>
          <li><a href="../html/acerca_de.html" style="text-decoration:none; color:#333; font-weight:500;">Acerca de</a></li>
          <li><a href="register.php" style="text-decoration:none; color:#333; font-weight:500;">Registrarse</a></li>
        </ul>
      </nav>

    </div>
  </header>

  <main style="flex:1; display:flex; justify-content:center; align-items:center;">
    <div style="width:100%; max-width:500px; background:#fff; padding:50px; border-radius:10px; box-shadow:0 3px 8px rgba(0,0,0,0.1); text-align:center;">
      <h1 style="margin-bottom:25px;">Iniciar sesión</h1>

      <!-- Mensajes de error -->
      <?php if (isset($_GET['msg'])): ?>
        <div class="msg" style="margin-bottom:15px;">
          <?php
            if ($_GET['msg'] == 'error_pass') echo "<span style='color:red;'>Contraseña incorrecta</span>";
            if ($_GET['msg'] == 'error_user') echo "<span style='color:red;'>Usuario no encontrado</span>";
            if ($_GET['msg'] == 'logout') echo "<span style='color:green;'>Sesión cerrada correctamente</span>";
          ?>
        </div>
      <?php endif; ?>

      <form action="login_action.php" method="POST" style="display:flex; flex-direction:column; gap:15px;">
        <input type="text" name="email" placeholder="Correo electrónico" required 
               style="padding:12px; border:1px solid #ccc; border-radius:5px; font-size:15px;">
        <input type="password" name="password" placeholder="Contraseña" required 
               style="padding:12px; border:1px solid #ccc; border-radius:5px; font-size:15px;">
        <button type="submit" 
                style="padding:12px; background-color:#28a745; border:none; color:white; font-size:16px; border-radius:5px; cursor:pointer;">
          Iniciar sesión
        </button>
      </form>
    </div>
  </main>

</body>
</html>

<?php
session_start();          
include 'conexion.php';

// Verificar que el usuario sea admin
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php?msg=error_user");
    exit;
}

// Consultas para estadísticas
$totalProductos = $conn->query("SELECT COUNT(*) AS total FROM productos")->fetch_assoc()['total'];
$totalCategorias = $conn->query("SELECT COUNT(DISTINCT categoria) AS total FROM productos")->fetch_assoc()['total'];
$totalStock = $conn->query("SELECT SUM(stock) AS total FROM productos")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 40px;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.12);
      padding: 20px;
      text-align: center;
    }
    .card h3 {
      margin-bottom: 10px;
      color: #3eb43a;
    }
    .card p {
      font-size: 22px;
      font-weight: bold;
      color: #323337;
    }
  </style>
</head>
<body>

<header class="header">
        <div class="nav-container container">
            <a href="index.html">
                <img src="../imagenes/logo_pistachon.png" alt="El Pistachón" class="logo-img">
            </a>
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle">
                <img src="../imagenes/menu_ico.png" class="menu-icon" alt="Menú">
            </label>
            <nav class="navbar">
                <ul>
                    <li><a href="agregar_producto.php">Agregar Producto</a></li>
                    <li><a href="listar_productos.php">Gestionar Productos</a></li>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </div>

        <div class="hero-content container">
            <h1>Panel de Administrador</h1>
            <p>
                Agrega o gestiona tus productos en las opciones del menú.
            </p>
        </div>
    </header>

<section class="container" style="padding:40px;">
  <h1>Bienvenido Administrador</h1>
  <p>Hola, <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>.  
  Has iniciado sesión correctamente como <b>administrador</b>.</p>

  <!-- Dashboard visual -->
  <div class="dashboard">
    <div class="card">
      <h3>Total de Productos</h3>
      <p><?php echo $totalProductos; ?></p>
    </div>
    <div class="card">
      <h3>Categorías</h3>
      <p><?php echo $totalCategorias; ?></p>
    </div>
    <div class="card">
      <h3>Stock Disponible (kg)</h3>
      <p><?php echo $totalStock ?? 0; ?></p>
    </div>
  </div>
</section>

</body>
</html>
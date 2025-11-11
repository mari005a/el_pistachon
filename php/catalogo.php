<?php
include 'conexion.php';

// Filtros de búsqueda
$categoria = $_GET['categoria'] ?? '';
$buscar = $_GET['buscar'] ?? '';

$sql = "SELECT * FROM productos WHERE 1=1";
$params = [];
$types = "";

// Filtrar por categoría
if (!empty($categoria)) {
    $sql .= " AND categoria = ?";
    $params[] = $categoria;
    $types .= "s";
}

// Filtrar por búsqueda en nombre o descripción
if (!empty($buscar)) {
    $sql .= " AND (nombre LIKE ? OR descripcion LIKE ?)";
    $params[] = "%$buscar%";
    $params[] = "%$buscar%";
    $types .= "ss";
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Catálogo | El pistachón</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header class="header">

        <div class="nav-container container">
            <a href="index.html" class="logo">Logo</a> <!--Insertar imagen para el logo-->
            <input type="checkbox" id="menu-toggle">
            <label for="menu">
                <img src="../imagenes/menu_ico.png" class="menu-icon" alt="Menú">
            </label>

            <nav class="navbar">
                <ul>
                    <li><a href="../html/index.html">Inicio</a></li>
                    <li><a href="catalogo.php">Catálogo</a></li>
                    <li><a href="../html/acerca_de.html">Acerca de</a></li>
                    <li><a href="login.php">Inicio de Sesión</a></li>

                </ul>
            </nav>

        </div>

        <div class="hero-content container">

            <h1>Catálogo</h1>
            <p>
                ¡Bienvenidos a nuestra tienda!
                En este sitio web encontrarás todo lo que necesitas saber sobre nuestros productos: desde los distintos tipos de chiles que ofrecemos, hasta productos percederos. 
                También te invitamos a conocer la historia que dio origen a este proyecto. ¡Gracias por visitarnos!
            </p>
        </div>

    </header>

<section class="search-section">
  <div class="search-container">
    <form method="GET" action="catalogo.php">
      <select name="categoria" class="category-select">
        <option value="">Todas las categorías</option>
        <option value="chiles">Chiles</option>
        <option value="especias">Especias</option>
        <option value="semillas">Semillas</option>
        <option value="dulces">Dulces</option>
        <option value="frutas-secas">Frutas Secas</option>
        <option value="otros">Otros</option>
      </select>
      <input type="text" name="buscar" placeholder="Buscar producto..." class="search-input">
      <button type="submit" class="search-button">
        <img src="../imagenes/quelepasaalupita.jpg" alt="Buscar" class="lupa-icono">
      </button>
    </form>
  </div>
</section>

<div class="catalog-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-top: 32px;">
  <?php while ($row = $result->fetch_assoc()): ?>
    <div>
      <div class="catalog-info" style="background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.12); border-radius: 12px; padding: 12px; margin-top: 8px; padding-top: 25px;">
        <img src="../imagenes/<?php echo htmlspecialchars($row['foto']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>" class="catalog-img" style="padding-bottom: 20px;">
        <h4><?php echo htmlspecialchars($row['nombre']); ?></h4>
        <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
        <span>Stock: <?php echo htmlspecialchars($row['stock']); ?></span>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<footer class="footer">

    <div class="footer-content container">
        <div class="link">
            <h3>Sobre Nosotros</h3>
            <ul>
                <li><a href="acerca_de.html">Trabaja con nosotros</a></li>
                <li><a href="acerca_de.html">Negocio</a></li>
                <li><a href="acerca_de.html">Proveedores</a></li>
            </ul>
        </div>
        <div class="link">

            <h3>Atención al cliente</h3>
            <ul>
                <li><a href="acerca_de.html">Tel: 646-128-5183</a></li>
            </ul>
        </div>
    </div>

</footer>

</body>
</html>
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
          <a href="../html/index.html">
                <img src="../imagenes/logo_pistachon.png" alt="El Pistachón" class="logo-img">
            </a>
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle">
                <img src="../imagenes/menu_ico.png" class="menu-icon" alt="Menú">
            </label>
            <nav class="navbar">
                <ul>
                    <li><a href="../html/index.html">Inicio</a></li>
                    <li><a href="catalogo.php">Catálogo</a></li>
                    <li><a href="../html/acerca_de.html">Acerca de</a></li>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                </ul>
            </nav>
        </div>

        <div class="hero-content container">
            <h1>Catálogo</h1>
            <p>
                Explora nuestra amplia variedad de productos y encuentra lo que necesitas para tu hogar o negocio.
            </p>
        </div>

    </header>

<section class="search-section">
  <div class="search-container">
    <form method="GET" action="catalogo.php" style="display:flex; flex-wrap:wrap; align-items:center; gap:8px;">
      <select name="categoria" class="category-select" style="padding:8px;">
        <option value="">Todas las categorías</option>
        <option value="chiles">Chiles</option>
        <option value="especias">Especias</option>
        <option value="semillas">Semillas</option>
        <option value="dulces">Dulces</option>
        <option value="frutas-secas">Frutas Secas</option>
        <option value="otros">Otros</option>
      </select>

      <input type="text" name="buscar" placeholder="Buscar producto..." class="search-input" style="padding:8px; flex:1; min-width:180px;">

      <button type="submit" class="search-button" style="padding:8px 10px; display:inline-flex; align-items:center; justify-content:center;">
        <img src="../imagenes/quelepasaalupita.png" alt="Buscar" class="lupa-icono" style="width:20px; height:20px;">
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
        <span>Precio: <?php echo htmlspecialchars($row['precio']); ?></span>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<footer class="footer">
        <div class="footer-content container">
            <div class="footer-section">
                <h3>Sobre Nosotros</h3>
                <ul>
                    <li><a href="../html/acerca_de.html">Historia</a></li>
                    <li><a href="../html/acerca_de.html">Localización</a></li>
                    <li><a href="../html/acerca_de.html">Redes sociales</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Atención al cliente</h3>
                <ul>
                <li><a href="https://wa.me/526461285183">Tel: 646-128-5183</a></li>
                </ul>
            </div>
        </div>
    </footer>

</body>
</html>
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <form method="GET" action="catalogo.php" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
      <select name="categoria" class="category-select">
        <option value="">Todas las categorías</option>
        <option value="chiles">Chiles</option>
        <option value="especias">Especias</option>
        <option value="semillas">Semillas</option>
        <option value="dulces">Dulces</option>
        <option value="frutas-secas">Frutas Secas</option>
        <option value="otros">Otros</option>
      </select>

      <div style="display: flex; align-items: center; flex: 1; min-width: 250px;">
        <input type="text" name="buscar" placeholder="Buscar producto..." class="search-input" style="border-radius: 30px 0 0 30px; border-right: none;">
        
        <button type="submit" class="search-button" style="border-radius: 0 30px 30px 0; width: 50px; height: 42px; display: flex; align-items: center; justify-content: center;">
          <img src="../imagenes/quelepasaalupita.png" alt="Buscar" class="lupa-icono">
        </button>
      </div>
    </form>
  </div>
</section>

<div class="container">
    <div class="catalog-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="catalog-card">
                    <div class="catalog-info">
                        <img 
                            src="../imagenes/<?php echo htmlspecialchars($row['foto']); ?>" 
                            alt="<?php echo htmlspecialchars($row['nombre']); ?>" 
                            class="catalog-img"
                            onclick="openModal(this)"
                        >
                        <h4><?php echo htmlspecialchars($row['nombre']); ?></h4>
                        <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                        <span class="price">$<?php echo htmlspecialchars($row['precio']); ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">
                <p>No se encontraron productos con los filtros seleccionados.</p>
            </div>
        <?php endif; ?>
    </div>
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

<!-- Modal para ampliar imagen -->
<div id="imgModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImg" onclick="event.stopPropagation();">
</div>

<script>
function openModal(img) {
    const modal = document.getElementById("imgModal");
    const modalImg = document.getElementById("modalImg");
    
    modal.style.display = "flex";
    modalImg.src = img.src;
    modalImg.alt = img.alt;
    
    // Deshabilitar scroll del body
    document.body.style.overflow = "hidden";
}

function closeModal() {
    const modal = document.getElementById("imgModal");
    modal.style.display = "none";
    
    // Habilitar scroll del body
    document.body.style.overflow = "auto";
}

// Cerrar modal al hacer clic fuera de la imagen
document.getElementById('imgModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Cerrar con tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === "Escape") {
        closeModal();
    }
});
</script>

</body>
</html>